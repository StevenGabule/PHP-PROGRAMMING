<?php

// $a = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
// $b = &$a;
// foreach ($a as $v) {
// 	printf("%2d\n", $v);
// 	unset($a[1]);
// }

// $a = [1, 2, 3];
// foreach ($a as &$v) {
// 	printf("%2d - %2d\n", $v, current($a));
// }

// $a = [1];
// foreach ($a as &$v) {
// 	printf("%2d -\n", $v);
// 	$a[1] = 2;
// }

// $a=[1,2,3,4];
// foreach($a as &$v) {
// echo "$v\n";
// array_pop($a);
// }

$a = [0, 1, 2, 3];
foreach ($a as &$x) {
	foreach ($a as &$y) {
		echo "$x - $y\n";
		if ($x == 0 && $y == 1) {
			unset($a[1]);
			unset($a[2]);
		}
	}
}
