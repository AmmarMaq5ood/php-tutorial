<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            $('#loginForm').submit(function (event) {
                event.preventDefault();
                var form = $(this);
                var username = $('#username').val();
                var password = $('#password').val();

                if (username.trim() === '') {
                    $('#usernameError').text('Username is required');
                    return;
                } else {
                    $('#usernameError').text('');
                }

                if (password.trim() === '') {
                    $('#passwordError').text('Password is required');
                    return;
                } else {
                    $('#passwordError').text('');
                }

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            alert('Login Successful');
                            window.location.href = "index.php";
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
        });
    </script>
</head>

<body>
    <?php include "includes/navbar.php" ?>
    <div class="container p-2 mt-2">
        <h2>Login</h2>
        <form id="loginForm" method="post" action="login_process.php" class="needs-validation" novalidate>
            <div class="form-group py-2">
                <label class="fw-bold" for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
                <div id="usernameError" class="text-danger"></div>
            </div>
            <div class="form-group py-2">
                <label class="fw-bold" for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div id="passwordError" class="text-danger"></div>
            </div>
            <button type="submit" class="btn btn-primary py-2">Login</button>
        </form>
        <p class="py-2">Haven't got an account? <a class="link-underline link-underline-opacity-0"
                href="register.php">Register</a> now!</p>
    </div>
</body>

</html>