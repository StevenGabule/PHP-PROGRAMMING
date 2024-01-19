<?php 

define('DB_CONFIG_FILE', '/../data/config/db.config.php');
define('CSV_FILE', '/../data/files/prospects.csv');
require __DIR__ . '/../../Application/Autoload/Loader.php';
Application\Autoload\Loader::init(__DIR__ . '/..');

$connection = new Application\Database\Connection(include __DIR__ . DB_CONFIG_FILE);
$iterator = (new Application\Iterator\LargeFile(__DIR__ . CSV_FILE))->getIterator('Csv');

$sql = 'INSERT INTO `prospects` '
				. '(`id`,`first_name`,`last_name`,`address`,`city`,`state_province`,'
				. '`postal_code`,`phone`,`country`,`email`,`status`,`budget`, `last_updated`) '
				. ' VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
$statement = $connection->pdo->prepare($sql);
foreach ($iterator as $row) {
	echo implode(',', $row) . PHP_EOL;
	$statement->execute($row);
}