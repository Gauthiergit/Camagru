<?php
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');

$DB_DSN = "pgsql:host=$host;port=$port;dbname=$dbname";
$DB_USER = getenv('DB_USER');
$DB_PASSWORD = getenv('DB_PASSWORD');
?>