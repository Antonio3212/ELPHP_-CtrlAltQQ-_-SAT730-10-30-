<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents("php://input"));

    // Extract individual values from the JSON
    $first_name = $data->first_name;
    $last_name = $data->last_name;
    $email = $data->email;
    $password = $data->password;
    $phone = $data->phone;

    // Check if any field is empty
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($phone)) {
        echo json_encode(["status" => "failure", "message" => "All fields are required."]);
        exit();
    }

    try {
        // Connect to the SQLite database
        $db = new PDO('sqlite:' . __DIR__ . '/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if email already exists in the 'buyers' table
        $stmt = $db->prepare("SELECT * FROM buyers WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch()) {
            echo json_encode(["status" => "failure", "message" => "Email is already taken."]);
            exit();
        }

        // Hash the password before binding it
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the insert query for the 'buyers' table
        $stmt = $db->prepare("INSERT INTO buyers (first_name, last_name, email, password, phone) 
                              VALUES (:first_name, :last_name, :email, :password, :phone)");

        // Bind values to the prepared statement
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword); // Using hashed password
        $stmt->bindParam(':phone', $phone);

        // Execute the insert statement
        $stmt->execute();

        // Respond with success
        echo json_encode(["status" => "success", "message" => "Buyer registered successfully."]);
    } catch (PDOException $e) {
        // Handle database connection or query errors
        echo json_encode(["status" => "failure", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "failure", "message" => "Invalid request method."]);
}
?>
