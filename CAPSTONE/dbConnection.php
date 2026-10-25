<?php

require_once __DIR__ . '/repositories/Database.php';

$database = new Database();
$connection = $database->connect();

echo "SUCCESS! Database is connected.";
?>