<?php

namespace Application\Web;

use Exception;
use SplFileObject;

class Access
{
	const ERROR_UNABLE = 'ERROR: unable to open file';
	protected $log;
	public $frequency = array();

	public function __construct($filename)
	{
		if (!file_exists($filename)) {
			$message = __METHOD__ . ' : ' . self::ERROR_UNABLE . PHP_EOL;
			$message .= strip_tags($filename) . PHP_EOL;
			throw new Exception($message);
		}
		$this->log = new SplFileObject($filename, 'r');
	}

	public function fileIteratorByLine()
	{
		$count = 0;
		while (!$this->log->eof()) {
			yield $this->log->fgets();
			$count++;
		}
		return $count;
	}

	public function getIp($line)
	{
		preg_match('/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/', $line, $match);
		return $match[1] ?? '';
	}
}

define('LOG_FILES', '/var/log/apache2/*access*.log');
// define functions
$freq = function ($line) {
	$ip = $this->getIp($line);
	if ($ip) {
		echo '.';
		$this->frequency[$ip] = (isset($this->frequency[$ip])) ? $this->frequency[$ip] + 1 : 1;
	}
};

foreach (glob(LOG_FILES) as $filename) {
	echo PHP_EOL . $filename . PHP_EOL;
	$access = new Access($filename);
	foreach($access->fileIteratorByLine() as $line) {
		$freq->call($access, $line);
	}
	arsort($access->frequency);
	foreach($access->frequency as $key => $value) {
		printf('%16s : %6d' . PHP_EOL, $key, $value);
	}
}