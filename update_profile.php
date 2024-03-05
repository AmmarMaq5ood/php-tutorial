<?php
session_start();
include 'db_connection.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $original_username = $_POST["original_username"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $description = $_POST["description"];
    $tagline = $_POST["tagline"];

    if (!empty($_FILES["profile_picture"]["name"])) {
        $target_dir = __DIR__ . "/uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = basename($_FILES["profile_picture"]["name"]);
            $sql_update_picture = "UPDATE users SET profile_picture='$profile_picture' WHERE username='$original_username'";
            if ($conn->query($sql_update_picture) !== TRUE) {
                $response['success'] = false;
                $response['message'] = "Error updating profile picture: " . $conn->error;
                echo json_encode($response);
                exit();
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Sorry, there was an error uploading your file.";
            echo json_encode($response);
            exit();
        }
    }

    if (!empty($_FILES['image_gallery']['name'][0])) {
        $target_dir = __DIR__ . "/uploads/";
        $fileNames = $_FILES['image_gallery']['name'];
        $imagePaths = [];

        foreach ($fileNames as $key => $fileName) {
            $target_file = $target_dir . basename($_FILES["image_gallery"]["name"][ $key ]);

            if (move_uploaded_file($_FILES["image_gallery"]["tmp_name"][ $key ], $target_file)) {
                $imagePaths[] = basename($_FILES["image_gallery"]["name"][ $key ]);
            } else {
                $response['success'] = false;
                $response['message'] = "Error uploading images.";
                echo json_encode($response);
                exit();
            }
        }

        $sql_select_image_gallery = "SELECT image_gallery FROM users WHERE username='$original_username'";
        $result = $conn->query($sql_select_image_gallery);
        $currentImageGallery = [];

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentImageGallery = json_decode($row['image_gallery'], true);
            if ($currentImageGallery === null) {
                $currentImageGallery = [];
            }
        }

        $updatedImageGallery = array_merge($currentImageGallery, $imagePaths);

        $imageGalleryJson = json_encode($updatedImageGallery);
        $sql_update_images = "UPDATE users SET image_gallery='$imageGalleryJson' WHERE username='$original_username'";
        if ($conn->query($sql_update_images) !== TRUE) {
            $response['success'] = false;
            $response['message'] = "Error updating image gallery: " . $conn->error;
            echo json_encode($response);
            exit();
        }
    }

    $sql = "UPDATE users SET email='$email', description='$description', tagline='$tagline' WHERE username='$original_username'";
    if ($conn->query($sql) === TRUE) {
        if ($original_username !== $username) {
            $sql_update_username = "UPDATE users SET username='$username' WHERE username='$original_username'";
            if ($conn->query($sql_update_username) === TRUE) {
                $_SESSION['username'] = $username;
            } else {
                $response['success'] = false;
                $response['message'] = "Error updating username: " . $conn->error;
                echo json_encode($response);
                exit();
            }
        }

        $response['success'] = true;
        $response['message'] = "Profile updated successfully";
        echo json_encode($response);
    } else {
        $response['success'] = false;
        $response['message'] = "Error updating profile: " . $conn->error;
        echo json_encode($response);
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method";
    echo json_encode($response);
}