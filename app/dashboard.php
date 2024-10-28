<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once('db/db_connection.php');
$user_id = $_SESSION['user_id'];
// echo "THIS IS USER ID <h1> $user_id </h1>";
$sql = "SELECT username, email FROM users WHERE id = '$user_id'";
$result = $connection->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    header("Location: login.php");
    exit();
}

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchTerm)) {
    $searchSql = "SELECT id, title, content, created_at FROM posts WHERE user_id = '$user_id' AND (title LIKE '%$searchTerm%' OR content LIKE '%$searchTerm%') ORDER BY created_at DESC";
    $searchResult = $connection->query($searchSql);
} else {
    $postsSql = "SELECT id, title, content, created_at FROM posts WHERE user_id = '$user_id' ORDER BY created_at DESC";
    $searchResult = $connection->query($postsSql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }

        .card {
            width: 300px;
        }
    </style>
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
                <li class="nav-item">
                    <a class="nav-link" href="post.php">Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="posts.php">Posts</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 class="mt-3">Dashboard</h2>
        <form method="get" action="dashboard.php" class="mb-3">
            <div>

            </div>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search posts..." name="search" value="<?php echo $searchTerm; ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </div>
        </form>

        <?php
        if ($searchResult && $searchResult->num_rows > 0) {
            while ($post = $searchResult->fetch_assoc()) {
        ?>
                <div class="card mt-3">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $post['title']; ?></h4>
                        <p class="card-text"><?php echo $post['content']; ?></p>
                        <small class="text-muted">Posted on <?php echo $post['created_at']; ?></small>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p class='mt-3'>No posts found.</p>";
        }
        ?>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Welcome, <?php echo $user['username']; ?>!</h5>
                <p class="card-text">Email: <?php echo $user['email']; ?></p>
                <a href="logout.php" class="btn btn-danger btn-block">Logout</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>