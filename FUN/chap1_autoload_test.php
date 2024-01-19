<?php

require __DIR__ . './AutoLoad/Loader.php';
AutoLoad\Loader::init(__DIR__ . '/..');

$test = new Test\TestClass();
echo $test->getText();

