<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins (change to a specific domain in production)
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Allow POST and OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow necessary headers

// Handle preflight (OPTIONS) requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get user input
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data["email"]) || !isset($data["password"]) ) {
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

// Read existing users
$users = json_decode(file_get_contents("users.json"), true);

// Check if email already exists
foreach ($users as $user) {
    if ($user["email"] === $data["email"]) {
        echo json_encode(["error" => "Email already registered"]);
        exit;
    }
}

// Add new user
$newUser = [
    "name" => "",
    "email" => $data["email"],
    "password" => $data["password"],
    "photo" => ""
];
$users[] = $newUser;

// Save to JSON file
file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));

echo json_encode(["success" => true, "message" => "User registered successfully"]);
exit;
