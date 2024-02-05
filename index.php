<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Web App</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Your SHIT App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <a class="nav-link" href="index.php">Home</a>
                </li>
                <a class="nav-link" href="login.php">Login</a>
                </li>
                <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 class="mt-3">SHIT APP</h2>
        <p>Just simple and fuck webapp. Just for refreshing (;</p>
    </div>

</body>

</html>