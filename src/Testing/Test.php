<?php

namespace Testing;

class Test {
	
	private $id;

	private $description;

	private $testCases = [];

	public function __construct($id, $description) {
		$this->id = $id;
		$this->description = $description;
	}

	public function testCase($description, $func) {
		$id = count($this->testCases);
		$testCase = new TestCase($this->id.'#'.$id, $this->description."\n".$description, $func);
		$this->testCases[] = $testCase;

		return $testCase;
	}

	public function asArray() {
		return $this->testCases;
	}

}