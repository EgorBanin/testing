<?php

namespace Testing;

class TestCase {

	private $id;

	private $description;

	private $func;

	public function __construct($id, $description, $func) {
		$this->id = $id;
		$this->description = $description;
		$this->func = $func;
	}

	public function run() {
		$result = new Result($this->id,  $this->description);
		try {
			call_user_func($this->func, $result);
		} catch (FailException $e) {
			// FailException кидается для прерываания выполнения теста и не требует обработки
		}
		catch (\Exception $e) {
			$result->error($e->getMessage(), $e);
		}
		catch (\Throwable $e) {
			$result->error($e->getMessage(), $e);
		}

		return $result;
	}

}