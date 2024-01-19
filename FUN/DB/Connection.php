<?php

use Exception;
use PDO;

class Connection
{
	const ERROR_UNABLE = 'ERROR: Unable to create database connection';
	const ERROR_TYPE = 'ERROR: Type must be "ByLength", "ByLine" or "Csv"';
	public $pdo;
	protected $file;
	protected $allowedTypes = ['ByLine', 'ByLength', 'Csv'];
	
	public function __construct(array $config)
	{
		if (!isset($config['driver'])) {
			$message = __METHOD__ . ' : ' . self::ERROR_UNABLE . PHP_EOL;
			throw new Exception($message);
		}
		$dsn = $config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['dbname'];
		try {
			$this->pdo = new PDO($dsn, $config['user'], $config['password'], [PDO::ATTR_ERRMODE => $config['errmode']]);
		} catch (PDOException $e) {
			error_log($e->getMessage());
		}
	}

	protected function fileIteratorCsv() 
	{
		$count = 0;
		while (!$this->file->eof()) {
			yield $this->file->fgetcsv();
			$count++;
		}
		return $count;
	}
}
