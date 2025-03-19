<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight (OPTIONS) request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Read the users.json file
$usersFile = __DIR__ . "/users.json";
if (!file_exists($usersFile)) {
    echo json_encode(["error" => "User data not found"]);
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);

// Check if an email parameter is provided
$email = isset($_GET['email']) ? trim($_GET['email']) : null;

if ($email) {
    // Search for the specific user by email
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            echo json_encode([
                "success" => true,
                "user" => [
                    "name" => $user["name"],
                    "photo" => $user["photo"],
                    "email" => $user["email"]
                ]
            ]);
            exit;
        }
    }
    echo json_encode(["error" => "User not found"]);
} else {
    // Return all users with only name and photo
    $filteredUsers = array_map(function ($user) {
        return [
            "name" => $user["name"],
            "photo" => $user["photo"],
            "email" => $user["email"]
        ];
    }, $users);

    echo json_encode(["success" => true, "users" => $filteredUsers]);
}
exit;
?>
