<?php
/**
 *
 * @author Oleku
 *        
 */
class MultipartSplit {
	private $type;
	private $start;
	private $hash;
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
	public function getHash() {
		return $this->hash;
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
		$this->binary = $this->type == "string" && ctype_print($input) === false;
		$this->binary and $input = base64_encode($input);
		$input = json_encode($input);
		
		$this->hash = sha1($input);
		$this->length = strlen($input);
		
		return $input;
	}

	/**
	 * Decode Data
	 */
	public function decode($input) {
		$input = json_decode($input);
		$this->binary and $input = base64_decode($input);
		// fix json array to object issue
		$this->type == "array" and $input = (array) $input;
		return $input;
	}
}

?>