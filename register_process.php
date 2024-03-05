<?php

include 'db_connection.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $check_username_query = "SELECT * FROM users WHERE username='$username'";
    $check_email_query = "SELECT * FROM users WHERE email='$email'";

    $username_result = $conn->query($check_username_query);
    $email_result = $conn->query($check_email_query);

    if ($username_result->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = "Username already exists";
    } elseif ($email_result->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = "Email already exists";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql) == TRUE) {
            $response['success'] = true;
            $response['message'] = "User registered successfully";
        } else {
            $response['success'] = false;
            $response['message'] = "Error: " . $conn->error;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
