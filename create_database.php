<?php
$dbPath = 'seller_register.db';

if (!file_exists($dbPath)) {
    try {
        $db = new SQLite3($dbPath);

        $query = "
        CREATE TABLE IF NOT EXISTS sellers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            phone TEXT NOT NULL,
            shop_name TEXT NOT NULL,
            address TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";
        if ($db->exec($query)) {
            echo "Database and table created successfully.";
        } else {
            echo "Error creating database/table: " . $db->lastErrorMsg();
        }

        $db->close();
    } catch (Exception $e) {
        echo "Error opening database: " . $e->getMessage();
        exit();
    }
} else {
    echo "Database file already exists.";
}
?>
