<?php
require_once('db/db_connection.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);

    // Server-side validation
    if (empty($username) || empty($password) || empty($email)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

        try {
            if ($connection->query($sql) === TRUE) {
                echo "Registration successful!";
                Header("Location: /login.php");
            } else {
                $errors[] = "Error registering user. Please try again later.";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $errors[] = "Username or email already exists. Please choose a different one.";
            } else {
                $errors[] = "An unexpected error occurred. Please try again later.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }

        .card {
            width: 300px;
            margin: auto;
            margin-top: 80px;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="#">Your SHIT App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title text-center">Registration</h2>
            <?php
            if (!empty($errors)) {
                echo '<div class="alert alert-danger">';
                foreach ($errors as $error) {
                    echo "<p class='mb-0'>$error</p>";
                }
                echo '</div>';
            } elseif (isset($_POST['username'])) {
                echo '<div class="alert alert-success">Registration successful!</div>';
            }
            ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
            <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
</body>

</html>