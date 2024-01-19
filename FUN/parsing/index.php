<?php

// $foo = 'cat';
// $bar = 'baz';
// $cat = 'cats';
// echo $$foo; // returns cat

// references a multidimensional array with a bar key and a baz sub-key:
// $foo = 'bar';
// $bar = ['bar' => ['baz' => 'bat']];
// echo $$foo['bar']['baz']; // returns 'bat'

$bar = 'baz';
$foo = new class {
	public function __construct() 
	{
		$this->baz = ['bada' => function () { return 'boom'; }];
	}
};
echo $foo->$bar['bada']();