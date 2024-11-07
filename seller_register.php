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
    $shop_name = $data->shop_name;
    $address = $data->address;

    // Check if any field is empty
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($phone) || empty($shop_name) || empty($address)) {
        echo json_encode(["status" => "failure", "message" => "All fields are required."]);
        exit();
    }

    try {
        $db = new PDO('sqlite:' . __DIR__ . '/users.db'); // SQLite connection string
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if email already exists
        $stmt = $db->prepare("SELECT * FROM sellers WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch()) {
            echo json_encode(["status" => "failure", "message" => "Email is already taken."]);
            exit();
        }

        // Hash the password before binding it
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare insert query
        $stmt = $db->prepare("INSERT INTO sellers (first_name, last_name, email, password, phone, shop_name, address) 
                              VALUES (:first_name, :last_name, :email, :password, :phone, :shop_name, :address)");

        // Bind values
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword); // Now passing the hashed password variable
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':shop_name', $shop_name);
        $stmt->bindParam(':address', $address);

        // Execute the insert statement
        $stmt->execute();

        // Respond with success
        echo json_encode(["status" => "success", "message" => "User registered successfully."]);
    } catch (PDOException $e) {
        // Handle errors
        echo json_encode(["status" => "failure", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "failure", "message" => "Invalid request method."]);
}
?>
