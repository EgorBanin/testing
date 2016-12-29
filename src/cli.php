<?php

namespace Testing;

// bootstrap
$include = realpath(__DIR__);
set_include_path(get_include_path().PATH_SEPARATOR.$include);
spl_autoload_register(function($className) {
	$fileName = stream_resolve_include_path(
		strtr(ltrim($className, '\\'), '\\', '/').'.php'
	);
	
	if ($fileName) {
		require_once $fileName;
	}
});

// получаем список файлов-тестов
$file = isset($argv[1])? realpath($argv[1]) : realpath('./');
if (is_dir($file)) {
	$files = new \RecursiveIteratorIterator(
		new \RecursiveDirectoryIterator(
			$file,
			\RecursiveDirectoryIterator::SKIP_DOTS
			| \RecursiveDirectoryIterator::CURRENT_AS_PATHNAME
		),
		\RecursiveIteratorIterator::SELF_FIRST,
		\RecursiveIteratorIterator::CATCH_GET_CHILD
	);
} elseif (is_file($file)) {
	$files = [$file];
} else {
	$files = [];
	// todo show usage
}

// прогоняем все тесты
list($results,) = Runner::run($files, function($result) {
	switch ($result->getStatus()) {
		case Result::STATUS_FAIL:
			$fails[] = $result;
			echo 'F';
			break;

		case Result::STATUS_ERROR:
			$errors[] = $result;
			echo 'E';
			break;

		default:
			echo '.';
	}
});

// результат
foreach ($results as $result) {
	if ($result->getStatus() === Result::STATUS_OK) {
		continue;
	}
	echo "\n".$result->getId().' '.$result->getDescription().":\n";
	$err = $result->getError();
	if ($err) {
		echo "\t".$err[0]."\n";
	} else {
		$result->eachFail(function($type, $message) {
			echo "\t$type $message\n";
		});
	}
}