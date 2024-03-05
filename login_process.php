<?php
session_start();
include 'db_connection.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["username"] = $username;
            $response['success'] = true;
            $response['message'] = "Login successful";
        } else {
            $response['success'] = false;
            $response['message'] = "Incorrect password";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "User not found";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

header('Content-Type: application/json');
echo json_encode($response);