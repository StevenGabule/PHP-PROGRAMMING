<?php

function test()
{
	return [
		1 => function () {
			return [
				1 => function ($a) {
					return 'Level 1/1:' . ++$a;
				},
				2 => function ($a) {
					return 'Level 1/2:' . ++$a;
				},
			];
		},
		2 => function () {
			return [
				1 => function ($a) {
					return 'Level 2/1:' . ++$a;
				},
				2 => function ($a) {
					return 'Level 2/2:' . ++$a;
				},
			];
		}
	];
}

$a = 't';
$t = 'test';
echo $$a()[1]()[2](100); // Level 1/2:101

class Security
{
	public $filter;
	public $validate;

	public function __construct()
	{
		$this->filter = [
			'striptags' => function ($a) {
				return strip_tags($a);
			},
			'digits' => function ($a) {
				return preg_replace('/[^0-9]/', '', $a);
			},
			'alpha' => function ($a) {
				return preg_replace('/[^A-Z]/i', '', $a);
			}
		];

		$this->validate = [
			'alnum' => function ($a) {
				return ctype_alnum($a);
			},
			'digits' => function ($a) {
				return ctype_digit($a);
			},
			'alpha' => function ($a) {
				return ctype_alpha($a);
			}
		];
	}

	public function __call($method, $params)
	{
		preg_match('/^(filter|validate)(.*?)$/i', $method, $matches);
		$prefix = $matches[1] ?? '';
		$function = strtolower($matches[2] ?? '');
		if ($prefix && $function) {
			return $this->prefix[$function]($params[0]);
		}
		return $value;
	}
}

$data = [
	'<ul><li>Lots</li><li>of</li><li>Tags</li></ul>',
	12345,
	'This is a string',
	'String with number 12345',
];

$security = new Security();

foreach ($data as $item) {
	echo 'ORIGINAL: ' . $item . PHP_EOL;
	echo 'FILTERING' . PHP_EOL;
	printf('%12s : %s' . PHP_EOL, 'Strip Tags', $security->filterStripTags($item));
	printf('%12s : %s' . PHP_EOL, 'Digits', $security->filterDigits($item));
	printf('%12s : %s' . PHP_EOL, 'Alpha', $security->filterAlpha($item));
	echo 'VALIDATORS' . PHP_EOL;
	printf('%12s : %s' . PHP_EOL, 'Alnum', ($security->validateAlnum($item)) ? 'T' : 'F');
	printf('%12s : %s' . PHP_EOL, 'Digits', ($security->validateDigits($item)) ? 'T' : 'F');
	printf('%12s : %s' . PHP_EOL, 'Alpha', ($security->validateAlpha($item)) ? 'T' : 'F');
}
