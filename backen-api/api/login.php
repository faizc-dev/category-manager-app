<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins (change to a specific domain in production)
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Allow POST and OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow necessary headers

// Handle preflight (OPTIONS) requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Read JSON file
$users = json_decode(file_get_contents("users.json"), true);

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data["email"]) || !isset($data["password"])) {
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$email = $data["email"];
$password = $data["password"];

// Check if user exists
foreach ($users as $user) {
    if ($user["email"] === $email && $user["password"] === $password) {
        echo json_encode([
            "success" => true,
            "user" => ["name" => $user["name"], "email" => $user["email"],"photo" => $user["photo"],"role" => $user["role"]],
            "token" => base64_encode($user["email"] . ":" . time()) // Simple token
        ]);
        exit;
    }
}

echo json_encode(["error" => "Invalid email or password"]);
exit;
