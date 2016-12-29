<?php

namespace Testing;

$t = new Test(__FILE__, 'Тест теста');

return [

	 $t->testCase('В функцию теста приходит агрумент Result', function($r) {
		$result;
		$test = new TestCase('foo', 'bar', function($r) use(&$result) {
			$result = $r;
		});
		$test->run();
		$r->test('Результат внутри функции теста это '.Result::class, $result instanceof Result);
	}),

	$t->testCase('Метод run не пропускает исключения', function($r) {
		$result;
		$test = new TestCase('foo', 'bar', function($r) use(&$result) {
			$result = $r;
			throw new \Exception('Исключение из функции');
		});
		try {
			$test->run();
			$exception = null;
		} catch(\Exception $exception) {}
		$r->test('Исключение не выброшено', $exception === null);
	}),

];