<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    
    $email = $data->email;  
    $user_type = $data->user_type; 
    if (empty($email) || empty($user_type)) {
        echo json_encode(["status" => "failure", "message" => "Email and user_type are required."]);
        exit();
    }

    if (!in_array($user_type, ['buyer', 'seller'])) {
        echo json_encode(["status" => "failure", "message" => "Invalid user_type. Must be 'buyer' or 'seller'."]);
        exit();
    }

    try {
        $db = new PDO('sqlite:users.db'); 
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($user_type == 'buyer') {
            $stmt = $db->prepare("DELETE FROM buyers WHERE email = :email");
        } else {
            $stmt = $db->prepare("DELETE FROM sellers WHERE email = :email");
        }

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "$user_type deleted successfully."]);
        } else {
            echo json_encode(["status" => "failure", "message" => "$user_type not found."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "failure", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "failure", "message" => "Invalid request method."]);
}
?>
