<?php
/**
 *
 * @author Oleku
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
		
		// Convert to string
		$input = json_encode($input);
		
		// Get String hash
		$this->hashMD5 = md5($input);
		$this->hashSHA1 = sha1($input);
		
		$this->length = strlen($input);
		
		return $input;
	}

	/**
	 * Decode Data
	 */
	public function decode($input) {
		$input = json_decode($input);
		
		// Decode Binary
		$this->binary and $input = base64_decode($input);
		
		// Fix json array to object issue
		$this->type == "array" and $input = (array) $input;
		return $input;
	}
}

?>