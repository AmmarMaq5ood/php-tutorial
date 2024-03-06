<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+YE2Q7furb8d6z2p0a8plz1kksZf3g9aA/KO0so"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#updateProfileForm').submit(function (event) {
                event.preventDefault();
                var form = $(this);
                var formData = new FormData();

                var profilePictureInput = $('#profile_picture')[0].files[0];

                if (profilePictureInput) {
                    formData.append('profile_picture', profilePictureInput);
                }

                form.find(':input:not(:file)').each(function () {
                    var field = $(this);
                    formData.append(field.attr('name'), field.val());
                });

                var imageGalleryInput = $('#image_gallery')[0].files;
                for (var i = 0; i < imageGalleryInput.length; i++) {
                    formData.append('image_gallery[]', imageGalleryInput[i]);
                }

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.success) {
                            alert(data.message);
                            window.location.href = "profile.php";
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('An error occurred while processing your request.');
                    }
                });
            });

            $('#image_gallery').change(function () {
                var files = $('#image_gallery')[0].files;
                for (var i = 0; i < files.length; i++) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imageGalleryPreview').append('<img src="' + e.target.result + '" style="width: 75px" class="rounded-circle m-2">');
                    }
                    reader.readAsDataURL(files[i]);
                }
            });
        });
    </script>
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>
    <?php
    if (isset($_SESSION['username'])) {
        include 'db_connection.php';
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <div class="container p-2 mt-2">
                <h2>Welcome,
                    <?php echo $username; ?>
                </h2>
                <?php
                if (!$row['profile_picture']) {
                    echo '<img style="width: 75px" class="rounded-circle" src="assets/user.webp" alt="Profile Picture" class="profile-img rounded-circle">';
                } else {
                    echo '<img style="width: 75px" class="rounded-circle" src="uploads/' . $row['profile_picture'] . '" alt="Profile Picture" class="profile-img rounded-circle">';
                }
                ?>
                <form id="updateProfileForm" method="post" action="update_profile.php" enctype="multipart/form-data">
                    <input type="hidden" name="original_username" value="<?php echo $username; ?>">
                    <div class="form-group py-2">
                        <label class="fw-bold" for="profile_picture">Profile Picture:</label>
                        <input class="form-control" type="file" id="profile_picture" name="profile_picture">
                    </div>
                    <div class="form-group py-2">
                        <label class="fw-bold" for="username">Username:</label>
                        <input class="form-control" type="text" id="username" name="username"
                            value="<?php echo $row['username']; ?>">
                    </div>
                    <div class="form-group py-2">
                        <label class="fw-bold" for="email">Email:</label>
                        <input class="form-control" type="email" id="email" name="email" value="<?php echo $row['email']; ?>">
                    </div>
                    <div class="form-group py-2">
                        <label class="fw-bold" for="description">Description:</label>
                        <textarea class="form-control" id="description"
                            name="description"><?php echo $row['description']; ?></textarea>
                    </div>
                    <div class="form-group py-2">
                        <label class="fw-bold" for="tagline">Tagline:</label>
                        <input class="form-control" type="text" id="tagline" name="tagline"
                            value="<?php echo $row['tagline']; ?>">
                    </div>
                    <div class="form-group py-2">
                        <label class="fw-bold" for="image_gallery">Image Gallery:</label>
                        <input class="form-control" type="file" id="image_gallery" name="image_gallery[]" multiple>
                        <div class="bg-secondary-subtle rounded shadow-sm mt-2" id="imageGalleryPreview"></div>
                    </div>
                    <div class="form-group py-2">
                        <button type="submit" class="btn btn-primary py-2">Update Profile</button>
                    </div>
                </form>
            </div>
            <?php
        } else {
            echo "User not found";
        }
        $conn->close();
    } else {
        echo "Please log in to view your profile.";
    }
    ?>
</body>

</html>