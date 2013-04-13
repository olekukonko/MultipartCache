<?php

/**
 *
 * @author Oleku
 *        
 */
class MultipartChunk {
	private $data;
	private $next;
	private $offset;
	private $length;
	private $position;

	function __construct($data, $next, $offset, $position) {
		$this->data = $data;
		$this->next = $next;
		$this->offset = $offset;
		$this->position = $position;
		$this->length = strlen($data);
	}

	/**
	 * Get next key
	 * 
	 * @return string
	 */
	function getNext() {
		return $this->next;
	}

	/**
	 * Get Chunk Data
	 * 
	 * @return string
	 */
	function getData() {
		return $this->data;
	}
}

?>