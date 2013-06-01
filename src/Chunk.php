<?php

/**
 *
 * @author Oleku Konko
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/olekukonko/MultipartCache
 *      
 */
namespace Mcache;

class Chunk {
	private $data;
	private $next;
	private $offset;
	private $length;
	private $position;

	/**
	 *
	 * @param string $data
	 * @param string $next
	 * @param int $offset
	 * @param int $position
	 */
	public function __construct($data, $next, $offset, $position) {
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
	public function getNext() {
		return $this->next;
	}

	/**
	 * Get Chunk Data
	 *
	 * @return string
	 */
	public function getData() {
		return $this->data;
	}
}

?>