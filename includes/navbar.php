<nav class="navbar bg-primary navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand text-white fw-bold" href="index.php">Home</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <?php
            session_start();
            if (isset($_SESSION['username'])) {
                include 'db_connection.php';

                $username = $_SESSION['username'];
                $sql = "SELECT * FROM users WHERE username='$username'";
                $result = $conn->query($sql);
                echo '<div class="row align-items-center">';
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (!$row['profile_picture']) {
                        echo '<img style="width: 75px" class="rounded-circle" src="assets/user.webp" alt="Profile Picture" class="profile-img rounded-circle">';
                    } else {
                        echo '<img style="width: 75px" class="rounded-circle" src="uploads/' . $row['profile_picture'] . '" alt="Profile Picture" class="profile-img rounded-circle">';
                    }
                }

                echo '<div class="col-auto text-white">Welcome, <a class="text-white fw-bold link-underline link-underline-opacity-0" href="profile.php">' . $_SESSION['username'] . '</a></div>';
                echo '<div class="col-auto"><button type="button" class="btn btn-danger"><a class="text-white link-underline link-underline-opacity-0" href="logout.php">LOG OUT</a></button></div>';
                echo '</div>';
            } else {
                echo '<div class="row align-items-center">';
                echo '<div class="col-auto"><button type="button" class="btn btn-light"><a class="text-primary link-underline link-underline-opacity-0" href="login.php">Login</a></button></div>';
                echo '</div>';
            }
            ?>
        </ul>
    </div>
</nav>