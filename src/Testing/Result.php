<?php

namespace Testing;

class Result {

	const STATUS_OK = 'ok';

	const STATUS_FAIL = 'fail';

	const STATUS_ERROR = 'error';

	private $id;

	private $description;

	private $stack = [];

	private $error;

	public function __construct($id, $description) {
		$this->id = $id;
		$this->description = $description;
	}

	public function assert($message, $val) {
		$this->stack[] = [$val, $message];

		if ( ! $val) {
			throw new FailException();
		}
	}

	public function test($message, $val) {
		$this->stack[] = [$val, $message];
	}

	public function error($message, $e) {
		$this->error = [$message, $e];
	}

	public function getId() {
		return $this->id;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getStatus() {
		if ($this->error) {
			return self::STATUS_ERROR;
		}

		$status = self::STATUS_OK;
		foreach ($this->stack as $val) {
			if ( ! $val[0]) {
				$status = self::STATUS_FAIL;
			}
		}

		return $status;
	}

	public function eachFail($func) {
		foreach ($this->stack as $val) {
			if ( ! $val[0]) {
				call_user_func_array($func, $val);
			}
		}
	}

	public function getError() {
		return $this->error;
	}

}