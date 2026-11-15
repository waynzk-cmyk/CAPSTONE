<?php
require_once __DIR__ . '/repositories/Database.php';

try {
    $connection = (new Database())->connect();
    echo "SUCCESS! Database is connected.<br><br>";

    echo "<strong>Tables found:</strong><br>";
    foreach ($connection->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN) as $table) {
        $count = $connection->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        echo "$table: $count row(s)<br>";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}