<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Get the requested URI
$request_uri = $_SERVER['REQUEST_URI'];

// Remove query parameters (if any)
$request_uri = parse_url($request_uri, PHP_URL_PATH);

// Route requests properly
if ($request_uri === '/api/register.php') {
    require __DIR__ . '/api/register.php';
} elseif ($request_uri === '/api/login.php') {
    require __DIR__ . '/api/login.php';
} elseif ($request_uri === '/api/update_profile.php') {
    require __DIR__ . '/api/update_profile.php';
} 
 elseif ($request_uri === '/api//get_profile.php.php') {
    require __DIR__ . '/api/profiles.php';
}else {
    http_response_code(404);
    echo json_encode(["error" => "Invalid API endpoint"]);
}
?>
