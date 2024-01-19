<?php

class Converter {
	const EXCEPTION_FILE_NOT_EXISTS = 'FIELD NOT EXISTS';
	public function scan($filename) 
	{
		if(!file_exists($filename)) {
			throw new Exception(self::EXCEPTION_FILE_NOT_EXISTS);
		}
		$contents = file($filename);
		echo 'Processing: ' . $filename . PHP_EOL;
		$result = preg_replace_callback_array([
			'!^\<\%(\n| )!' =>
				function ($match) {
					return '<?php' . $match[1];
				},
			// replace no-longer-supported opening tags
			'!^\<\%=(\n| )!' =>
				function ($match) {
					return '<?php echo ' . $match[1];
				},
			// replace no-longer-supported closing tag
			'!\%\>!' =>
				function ($match) {
					return '?>';
				},
				// changes in how $$xxx interpretation is handled
			'!(.*?)\$\$!' =>
				function ($match) {
				return '// WARNING: variable interpolation
				. ' now occurs left-to-right' . PHP_EOL
				. '// see: http://php.net/manual/en/'
				. '// migration70.incompatible.php'
				. $match[0];
				},
			// changes in how the list() operator is handled
			'!(.*?)list(\s*?)?\(!' =>
				function ($match) {
				return '// WARNING: changes have been made '
				. 'in list() operator handling.'
				. 'See: http://php.net/manual/en/'
				. 'migration70.incompatible.php'
				. $match[0];
				},
				// instances of \u{
				'!(.*?)\\\u\{!' =>
					function ($match) {
					return '// WARNING: \\u{xxx} is now considered '
					. 'unicode escape syntax' . PHP_EOL
					. '// see: http://php.net/manual/en/'
					. 'migration70.new-features.php'
					. '#migration70.new-features.unicode-'
					. 'codepoint-escape-syntax' . PHP_EOL
					. $match[0];
					},
					// relying upon set_error_handler()
					'!(.*?)set_error_handler(\s*?)?.*\(!' =>
					function ($match) {
					return '// WARNING: might not '
									. 'catch all errors'
									. '// see: http://php.net/manual/en/'
									. '// language.errors.php7.php'
									. $match[0];
					},
					// session_set_save_handler(xxx)
					'!(.*?)session_set_save_handler(\s*?)?\((.*?)\)!' =>
					function ($match) {
					if (isset($match[3])) {
					return '// WARNING: a bug introduced in'
						. 'PHP 5.4 which '
						. 'affects the handler assigned by '
						. 'session_set_save_handler() and '
						. 'where ignore_user_abort() is TRUE
						. 'has been fixed in PHP 7.'
						. 'This could potentially break '
						. 'your code under '
						. 'certain circumstances.' . PHP_EOL
						. 'See: http://php.net/manual/en/'
						. 'migration70.incompatible.php'
						. $match[0];
					} else {
						return $match[0];
					}
				},
				// wraps bit shift operations in try / catch
				'!^(.*?)(\d+\s*(\<\<|\>\>)\s*-?\d+)(.*?)$!' => function ($match) {
					return '// WARNING: negative and '
					. 'out-of-range bitwise '
					. 'shift operations will now
					. 'throw an ArithmeticError' . PHP_EOL
					. 'See: http://php.net/manual/en/'
					. 'migration70.incompatible.php'
					. 'try {' . PHP_EOL
					. "\t" . $match[0] . PHP_EOL
					. '} catch (\\ArithmeticError $e) {'
					. "\t" . 'error_log("File:"
					. $e->getFile()
					. " Message:" . $e->getMessage());'
					. '}' . PHP_EOL;
				},

				// replaces "call_user_method()" with
				// "call_user_func()"
				'!call_user_method\((.*?),(.*?)(,.*?)\)(\b|;)!' =>
				function ($match) {
					$params = $match[3] ?? '';
					return '// WARNING: call_user_method() has '
					. 'been removed from PHP 7' . PHP_EOL
					. 'call_user_func(['. trim($match[2]) . ','
					. trim($match[1]) . ']' . $params . ');';
				},
				// replaces "call_user_method_array()"
				// with "call_user_func_array()"
				'!call_user_method_array\((.*?),(.*?),(.*?)\)(\b|;)!' => function ($match) {
						return '// WARNING: call_user_method_array()'
						. 'has been removed from PHP 7'
						. PHP_EOL
						. 'call_user_func_array(['
						. trim($match[2]) . ','
						. trim($match[1]) . '], '
						. $match[3] . ');';
					},
					'!^(.*?)preg_replace.*?/e(.*?)$!' => function ($match) {
						$last = strrchr($match[2], ',');
						$arg2 = substr($match[2], 2, -1 * (strlen($last)));
						$arg1 = substr($match[0],
						strlen($match[1]) + 12,
						-1 * (strlen($arg2) + strlen($last)));
						$arg1 = trim($arg1, '(');
						$arg1 = str_replace('/e', '/', $arg1);
						$arg3 = '// WARNING: preg_replace() "/e" modifier
						. 'has been removed from PHP 7'
						. PHP_EOL
						. $match[1]
						. 'preg_replace_callback('
						. $arg1
						. 'function ($m) { return '
						. str_replace('$1','$m', $match[1])
						. trim($arg2, '"\'') . '; }, '
						. trim($last, ',');
						return str_replace('$1', '$m', $arg3);
					},
					// end array
					],
					// this is the target of the transformations
						$contents
					);
					// return the result as a string
						return implode('', $result);
					}
					]}
	}
}