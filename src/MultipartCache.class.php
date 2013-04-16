<?php
/**
 * Copyright 2012 Oleku Konko
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 * @author oleku
 *        
 *        
 */
class MultipartCache extends \Memcache {
	private $cache;
	private $limit = 1048576;
	private $stat;

	/**
	 * Set Cache Limit
	 * @param int $size
	 * @throws InvalidArgumentException
	 */
	function setLimit($size) {
		if ($size > 1048576) {
			throw new InvalidArgumentException("Size Can not be grater than 1024");
		}
		$this->limit = $size;
	}

	/**
	 *
	 * @param string $key
	 * @param string $flag
	 * @return \MultipartSplit stdClass
	 */
	function getDetails($key, $flag = MEMCACHE_COMPRESSED) {
		$details = parent::get($key, $flag);
		
		if ($details instanceof \MultipartSplit)
			return $details;
		
		$profile = new stdClass();
		$profile->type = gettype($details);
		$profile->saved = "direct";
		return $profile;
	}

	/**
	 * Get Values from Cache
	 *
	 * @see Memcache::get()
	 */
	function get($key, $flag = MEMCACHE_COMPRESSED) {
		
		// var_dump($this->getstats(),$this->getextendedstats());
		$split = parent::get($key, $flag);
		if (! $split instanceof \MultipartSplit)
			return $split;
		$data = "";
		$part = parent::get($split->getStart(), $flag);
		$data .= $part->getData();
		
		while ( $part->getNext() ) {
			$part = parent::get($part->getNext(), $flag);
			$data .= $part->getData();
		}
		
		if (sha1($data) != $split->getHashSHA1() || md5($data) != $split->getHashMD5())
			throw new Exception("Data Corrupted SHA1 & MD5 hash do not match");
		
		return $split->decode($data);
	}

	/**
	 * Add Values to Cache
	 *
	 * @see Memcache::set()
	 */
	function set($key, $var, $flag = MEMCACHE_COMPRESSED, $exp = null) {
		$split = new MultipartSplit($this->limit);
		$var = $split->encode($var);
		
		if ($split->getLength() < $this->limit) {
			return parent::set($key, $var, $flag, $exp);
		}
		
		$start = $slices = 0;
		$split->setStart(sprintf("%s-%s", $key, $start));
		
		$dataPart = substr($var, $start, $this->limit);
		while ( $dataPart ) {
			$nextOffset = $start + $this->limit;
			$next = $nextOffset >= $split->getLength() ? false : $key . "-" . $nextOffset;
			if (! parent::set(sprintf("%s-%s", $key, $start), new MultipartChunk($dataPart, $next, $start, $slices), $flag, $exp))
				return false;
			$start += $this->limit;
			$slices ++;
			$dataPart = substr($var, $start, $this->limit);
		}
		$split->setSlices($slices);
		return parent::set($key, $split, $flag, $exp);
	}
}
?>