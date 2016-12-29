<?php

namespace Testing;

return new TestCase(__FILE__, 'Тест пускалки', function($r) {
	$existingFile = __FILE__;
	$test = Runner::loadTest($existingFile);
	$containsOnlyTestCases = array_reduce($test, function($carry, $item) {
		return $carry && ($item instanceof TestCase);
	}, true);
	$r->test('Результат загрузки массив инстансов '.Test::class, $containsOnlyTestCases);

	$noExistingFile = 'noExistingFile.php';
	$r->assert('Несуществующий файл не существует', ! file_exists($noExistingFile));
	$e = null;
	try {
		$test = Runner::loadTest($noExistingFile);
	} catch (Exception $e) {}
	$r->test('Брошено исключение о невозможности загрузить тест из несуществующего файла', $e instanceof Exception);
});
