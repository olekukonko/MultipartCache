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
class MultipartSplit {
	private $type;
	private $start;
	private $hashMD5;
	private $hashSHA1;
	private $limit;
	private $slices = 0;
	private $length = 0;
	private $binary = 0;

	/**
	 *
	 * @param int $limit        	
	 */
	function __construct($limit) {
		$this->limit = $limit;
	}

	/**
	 * Set total slices
	 *
	 * @param int $slices        	
	 */
	public function setSlices($slices) {
		$this->slices = (int) $slices;
	}

	/**
	 * Set the key for the first data
	 *
	 * @param unknown $start        	
	 */
	public function setStart($start) {
		$this->start = $start;
	}

	/**
	 * get Starting key
	 *
	 * @return string
	 */
	public function getStart() {
		return $this->start;
	}

	/**
	 * Get DATA SHA1 Hash
	 *
	 * @return string
	 */
	public function getHashSHA1() {
		return $this->hashSHA1;
	}

	/**
	 * Get DATA MD5 Hash
	 *
	 * @return string
	 */
	public function getHashMD5() {
		return $this->hashMD5;
	}

	/**
	 * Get Data type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get if data is binary
	 *
	 * @return boolean
	 */
	public function isBinary() {
		return $this->binary;
	}

	/**
	 * Return process string length
	 *
	 * @return number
	 */
	public function getLength() {
		return $this->length;
	}

	/**
	 * Encode Data
	 */
	public function encode($input) {
		$this->type = gettype($input);
		
		// Check if Binary
		$this->binary = $this->type == "string" && ctype_print($input) === false;
		
		// Base64 Encode if Binary
		$this->binary and $input = base64_encode($input);
		
		// Convert to JSON string / Msg Pack Binary
		$input = function_exists("msgpack_pack") ? msgpack_pack($input) : json_encode($input);
		
		//var_dump($input);
		
		// Get String hash
		$this->hashMD5 = md5($input);
		$this->hashSHA1 = sha1($input);
		
		$this->length = mb_strlen($input);
		
		return $input;
	}

	/**
	 * Decode Data
	 */
	public function decode($input) {
		
		// Convert from JSON string / Msg Pack Binary
		$input = function_exists("msgpack_unpack") ? msgpack_unpack($input) : json_decode($input);
		
		//var_dump($input);
		
		// Decode Binary
		$this->binary and $input = base64_decode($input);
		
		// Fix json array to object issue
		$this->type == "array" and $input = (array) $input;
		return $input;
	}
}

?>