<?php

namespace Testing;

class Runner {
	
	public static function loadTest($file) {
		if ( ! is_readable($file)) {
			throw new Exception('Невозможно прочитать файл '.$file);
		}

		$test = require $file;
		if (is_array($test)) {
			foreach ($test as $testCase) {
				if ( ! ($testCase instanceof TestCase)) {
					throw new Exception('Неверный результат подключения скрипта '.$file);
				}
			}
		} else {
			if ( ! ($test instanceof TestCase)) {
				throw new Exception('Неверный результат подключения скрипта '.$file);
			}
			$test = [$test];
		}

		return $test;
	}

	public static function run($files, $onResult = null, $onError = null) {
		$results = [];
		$errors = [];
		foreach ($files as $file) {
			if ( ! is_file($file)) {
				// пропускаем директории
				continue;
			}

			try {
				$test = self::loadTest($file);
			} catch(Exception $e) {
				if ($onError) {
					call_user_func($onError, $e);
				}
				$errors[] = [$file, $e->getMessage()];
				continue;
			}

			foreach ($test as $testCase) {
				$result = $testCase->run();
				if ($onResult) {
					call_user_func($onResult, $result);
				}
				$results[] = $result;
			}
		}

		return [$results, $errors];
	}

}