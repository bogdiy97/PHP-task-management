<?php
/**
 * Database Connection Configuration
 * 
 * @author Robu Bogdan
 * @version 1.0
 * @date 2024
 * 
 * Establishes secure PDO connection to MySQL database using environment variables
 */

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
	$lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lines as $line) {
		if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
			list($key, $value) = explode('=', $line, 2);
			$_ENV[trim($key)] = trim($value);
		}
	}
}

$sName = $_ENV['DB_HOST'] ?? 'localhost';
$uName = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$db_name = $_ENV['DB_NAME'] ?? 'task_management_db';

try {
	$conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo "Connection failed: ". $e->getMessage();
	exit;
}