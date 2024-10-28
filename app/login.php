<?php
require_once('db/db_connection.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errors[] = "Username and password are required.";
    }

    // if (empty($errors)) {
    //     $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    //     $result = $connection->query($sql);

    //     if ($result && $result->num_rows > 0) {
    //         $user = $result->fetch_assoc();

    //         if (password_verify($password, $user['password'])) {
    //             // تم كسم إسرئيل
    //             session_start();
    //             $_SESSION['user_id'] = $user['id'];
    //             header("Location: dashboard.php"); // Redirect to dashboard or home page
    //             exit();
    //         } else {
    //             $errors[] = "Invalid password. Please try again.";
    //         }
    //     } else {
    //         $errors[] = "Username not found. Please register if you don't have an account.";
    //     }
    // }

    if (empty($errors)) {
        $sql = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
        $result = $connection->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php"); // Redirect to dashboard or home page
            exit();
        } else {
            $errors[] = "Invalid username and password combination. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 20px;
        }

        .card {
            width: 300px;
            margin-top: 20px;
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
            <h2 class="card-title text-center">Login</h2>
            <?php
            if (!empty($errors)) {
                echo '<div class="alert alert-danger">';
                foreach ($errors as $error) {
                    echo "<p class='mb-0'>$error</p>";
                }
                echo '</div>';
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

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>