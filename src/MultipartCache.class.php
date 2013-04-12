<?php
/**
 * 
 * @author Oleku
 *
 */
class MultipartCache {
	private $cache;
	private $size = 1048576;
	private $lastSpit = null;

	function __construct() {
		$this->cache = new Memcache();
		$this->cache->addserver("127.0.0.1");
	}

	function setLimit($size) {
		if ($size > 1048576) {
			throw new Exception("Size Can not be grater than 1024");
		}
		$this->size = $size;
	}

	function getLastSplit() {
		return $this->lastSpit;
	}

	function get($key, $flags = null) {
		$split = $this->cache->get($key, $flags);
		if (! $split instanceof \MultipartSplit)
			return $split;
		
		$this->lastSpit = $split;
		$data = "";
		$part = $this->cache->get($split->start, $flags);
		
		$data .= $part->data;
		
		while ( $part->next ) {
			$part = $this->cache->get($part->next, $flags);
			$data .= $part->data;
		}
		
		if (sha1($data) != $split->hash)
			throw new Exception("Data Corrupted");
		
		$data = json_decode($data);
		
		if ($split->base64 === true) {
			$data = base64_decode($data);
		}
		
		// fix original type
		switch ($split->type) {
			case "array" :
				$data = (array) $data;
				break;
			case "integer" :
				$data = (integer) $data;
				break;
			case "double" :
				$data = (float) $data;
				break;
			default :
				break;
		}
		
		return $data;
	}

	function set($key, $var, $flag = null, $exp = null) {
		$split = new MultipartSplit();
		$split->type = gettype($var);
		// make it safe
		if ($split->type == "string" && ctype_print($var) === false) {
			$var = base64_encode($var);
			$split->base64 = true;
		}
		// We only store json
		$var = json_encode($var);
		
		$split->length = strlen($var);
		
		if ($split->length < $this->size) {
			unset($split);
			return $this->cache->set($key, $var, $flag, $exp);
		}
		
		$split->hash = sha1($var);
		
		$split->limit = $this->size;
		$split->slices = floor($split->length / $this->size);
		$split->start = $key . "-0";
		$this->cache->set($key, $split, $flag, $exp);
		$this->lastSpit = $split;
		
		$start = 0;
		$data = "";
		while ( $data = substr($var, $start, $this->size) ) {
			
			$part = new MultipartPart();
			$part->length = strlen($data);
			$part->data = $data;
			$part->offset = $start;
			if (($start + $this->size) < $split->length) {
				$part->next = $key . "-" . ($start + $this->size);
			} else {
				$part->next = false;
			}
			
			if (! $this->cache->set(sprintf("%s-%s", $key, $start), $part, $flag, $exp))
				return false;
			
			$start += $this->size;
		}
		return true;
	}
}
?>