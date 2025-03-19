<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight (OPTIONS) request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Ensure the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

// Get Authorization token from headers
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(["error" => "Unauthorized request. No token provided."]);
    exit;
}
$token = trim($headers['Authorization']); // Token sent from frontend

// Decode the token to extract the email (assuming simple base64 encoding)
$decodedToken = base64_decode($token);
$email = explode(":", $decodedToken)[0]; // Extract email part

// Read users.json
$usersFile = __DIR__ . "/users.json";
$users = json_decode(file_get_contents($usersFile), true);

// Find the user by email
$found = false;
foreach ($users as &$user) {
    if ($user['email'] === $email) {
        // Update the name if provided
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $user['name'] = $_POST['name'];
        }

        // Handle file upload if a new photo is provided
        if (!empty($_FILES['photo']['name'])) {
            $uploadDir = __DIR__ . "/uploads/";

            // Ensure uploads directory exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate a unique filename
            $fileName = time() . "_" . basename($_FILES['photo']['name']);
            $filePath = $uploadDir . $fileName;

            // Move uploaded file
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                $user['photo'] = "uploads/" . $fileName; // Store relative path in JSON
            } else {
                echo json_encode(["error" => "Failed to upload photo"]);
                exit;
            }
        }

        $found = true;
        break;
    }
}

// If user was not found, return an error
if (!$found) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

// Save the updated users.json file
file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

echo json_encode(["success" => true, "message" => "Profile updated successfully"]);
?>
