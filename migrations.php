<?php
include '/var/www/html/init.php';

function initMigration() {
    global $db_link;

    $result = mysqli_query($db_link, "SHOW TABLES LIKE 'migrations'");

    if (mysqli_num_rows($result) === 0) {
        $sql = "
            CREATE TABLE migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";

        return mysqli_query($db_link, $sql);
    }

    return true;
}

initMigration();
global $db_link;

$migrationFiles = glob(__DIR__ . '/migrations/*.php');

sort($migrationFiles);

foreach ($migrationFiles as $file) {
    $filename = basename($file);

    $result = mysqli_query(
        $db_link,
        "SELECT id FROM migrations WHERE migration = '" .
        mysqli_real_escape_string($db_link, $filename) . "'"
    );

    if (mysqli_num_rows($result) > 0) {
        echo "Skipping {$filename} (already executed)\r\n";
        continue;
    }

    echo "Running {$filename}\r\n";

    include $file;

    mysqli_query(
        $db_link,
        "INSERT INTO migrations (migration)
         VALUES ('" . mysqli_real_escape_string($db_link, $filename) . "')"
    );
}