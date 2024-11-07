<?php
// Include the Firebase JWT library
require_once 'vendor/autoload.php'; // Ensure to include the autoloader from Composer
use \Firebase\JWT\JWT;

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents("php://input"));

    // Extract individual values from the JSON
    $email = $data->email;
    $password = $data->password;

    // Check if any field is empty
    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "failure", "message" => "Email and password are required."]);
        exit();
    }

    try {
        // Connect to SQLite database (Make sure to provide the correct path to the database)
        $db = new PDO('sqlite:' . __DIR__ . '/seller_register.db'); // SQLite connection string
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare query to fetch the seller by email
        $stmt = $db->prepare("SELECT * FROM sellers WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the seller data
        $seller = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$seller) {
            echo json_encode(["status" => "failure", "message" => "Invalid email or password."]);
            exit();
        }

        // Verify the password (hashed password in the database)
        if (!password_verify($password, $seller['password'])) {
            echo json_encode(["status" => "failure", "message" => "Invalid email or password."]);
            exit();
        }

        // Set the secret key directly in the code
        $secretKey = '9e3d4a44f8c3e3b5c971d4b70d13c1a61f4b6e8a3b20d663a12814a204bf6b76'; // Hardcoded secret key

        // Generate JWT Token
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // jwt valid for 1 hour from the issued time
        $payload = array(
            "id" => $seller['id'],
            "email" => $seller['email'],
            "first_name" => $seller['first_name'],
            "last_name" => $seller['last_name'],
            "shop_name" => $seller['shop_name'],
            "exp" => $expirationTime
        );

        // Encode JWT (include algorithm as third parameter)
        $jwt = JWT::encode($payload, $secretKey, 'HS256'); // The third parameter 'HS256' specifies the algorithm

        // Respond with success and the JWT token
        echo json_encode([
            "status" => "success",
            "message" => "Login successful.",
            "token" => $jwt
        ]);
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(["status" => "failure", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "failure", "message" => "Invalid request method."]);
}
?>
