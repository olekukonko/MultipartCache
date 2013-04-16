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
class MultipartChunk {
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