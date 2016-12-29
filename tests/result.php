<?php

namespace Testing;

return new TestCase(__FILE__, 'Тест результата теста', function($r) {
	$result = new Result('foo', 'bar');
	$result->test('foo bar', false);
	$r->test('Статус FAIL', $result->getStatus() === Result::STATUS_FAIL);
});