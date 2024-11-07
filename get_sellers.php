<?php
header("Content-Type: application/json");

// Set a default response code (200 OK)
http_response_code(200);

try {
    // Connect to SQLite database
    $db = new PDO('sqlite:users.db');  // Ensure correct path to your database
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL query to get all sellers
    $stmt = $db->query("SELECT * FROM sellers");

    // Fetch all rows as an associative array
    $sellers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are any sellers in the database
    if (count($sellers) > 0) {
        // Return the list of sellers as JSON
        echo json_encode([
            "status" => "success",
            "sellers" => $sellers
        ]);
    } else {
        // If no sellers are found, return an empty array with status
        echo json_encode([
            "status" => "success",
            "sellers" => []
        ]);
    }

} catch (PDOException $e) {
    // Set the response code to 500 for server errors
    http_response_code(500);

    // If there's a database error, return failure with the error message
    echo json_encode([
        "status" => "failure",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
