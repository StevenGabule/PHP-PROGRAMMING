<?php
define('EXAMPLE_PATH', realpath(__DIR__ . '/../'));
require __DIR__ . '/../Application/Autoload/Loader.php';
Application\Autoload\Loader::init(__DIR__ . '/..');
$directory = new Application\Iterator\Directory(EXAMPLE_PATH);

try {
	echo 'Mimics "ls -l -R" ' . PHP_EOL;
	foreach ($directory->ls('*.php') as $info) {
		echo $info;
	}
	echo 'Mimics "dir /s" ' . PHP_EOL;
	foreach ($directory->dir('*.php') as $info) {
		echo $info;
	}
} catch (Throwable $e) {
	echo $e->getMessage();
}
