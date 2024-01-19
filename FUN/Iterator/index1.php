<?php

function showElements($iterator) 
{
	foreach ($iterator as $item) {
		echo $item . ' '; 
	}
}

$A = range('A', 'Z');
$a = range('a', 'z');
$num = range(1, 100);
$i = new ArrayIterator($a);
$ia = new ArrayIterator($a);
$inum = new ArrayIterator($num);
showElements($a) . PHP_EOL;
showElements($ia);
showElements($inum);
