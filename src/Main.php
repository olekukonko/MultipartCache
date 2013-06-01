<?php

/**
 *
 * @author Oleku Konko
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/olekukonko/Cache
 *      
 */
namespace Mcache;

if (! class_exists("Memcache")) {
	trigger_error("Install PHP Memcache");
	exit();
}


class Main extends \Memcache {
	private $cache;
	private $limit = 1048576;
	private $stat;
	
	/**
	 * Set Cache Limit
	 * @param int $size
	 * @throws InvalidArgumentException
	 */
	public function setLimit($size) {
		if ($size > 1048576) {
			throw new \InvalidArgumentException("Size Can not be grater than 1024");
		}
		$this->limit = $size;
	}

	/**
	 * Get Memcache Details
	 * @param string $key
	 * @param string $flag
	 * @throws Exception
	 * @return \Split stdClass
	 */
	public function getDetails($key) {
		$details = parent::get($key);
		
		if (! $details instanceof Split)
			throw new \Exception("Data is not a valid Multipart Cache");
		
		$profile = array();
		$profile['type'] = $details->getType();
		$profile['sha1'] = $details->getHashSHA1();
		$profile['md5'] = $details->getHashMD5();
		$profile['length'] = $details->getLength();
		$profile['slice'] = $details->getSlices();
		$profile['time'] = date("r", $details->getTime());
		return json_encode($profile, 128);
	}

	/**
	 * Get Values from Cache
	 * @see Memcache::get()
	 * @throws Exception
	 */
	public function get($key) {
		// var_dump($this->getstats(),$this->getextendedstats());
		$split = parent::get($key);
		
		if (! $split instanceof Split) {
			return $split;
		}
		$data = "";
		$part = parent::get($split->getStart());
		$data .= $part->getData();
		
		while($part->getNext()) {
			$part = parent::get($part->getNext());
			$data .= $part->getData();
		}
		
		if (sha1($data) != $split->getHashSHA1() || md5($data) != $split->getHashMD5())
			throw new \Exception("Data Corrupted SHA1 & MD5 hash do not match");
		
		return $split->decode($data);
	}

	/**
	 * Add Values to Cache
	 * @see Memcache::set()
	 */
	public function set($key, $var, $flag = MEMCACHE_COMPRESSED, $exp = null) {
		$split = new Split($this->limit);
		$data = $split->encode($var);
		
		if (strlen($data) < $this->limit) {
			return parent::set($key, $var, $flag, $exp);
		}
		
		unset($var); // Free space
		
		$start = $slices = 0;
		$split->setStart(sprintf("%s-%s", $key, $start));
		
		$dataPart = substr($data, $start, $this->limit);
		
		while($dataPart) {
			$nextOffset = $start + $this->limit;
			$next = $nextOffset >= $split->getLength() ? false : $key . "-" . $nextOffset;
			if (! parent::set(sprintf("%s-%s", $key, $start), new Chunk($dataPart, $next, $start, $slices), $flag, $exp))
				return false;
			$start += $this->limit;
			$slices ++;
			$dataPart = substr($data, $start, $this->limit);
		}
		$split->setSlices($slices);
		$split->setTime(time());
		return parent::set($key, $split, $flag, $exp);
	}
}
?>