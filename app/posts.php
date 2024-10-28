<?php
session_start();
require_once('db/db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email FROM users WHERE id = '$user_id'";
$result = $connection->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    header("Location: login.php");
    exit();
}

$postsSql = "SELECT id, title, content FROM posts WHERE user_id = '$user_id'";
$postsResult = $connection->query($postsSql);

$posts = [];
if ($postsResult && $postsResult->num_rows > 0) {
    while ($row = $postsResult->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Posts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="text-center">
    <div class="container">
        <h2 class="mt-3">Your Posts</h2>

        <?php if (!empty($posts)) : ?>
            <ul class="list-group">
                <?php foreach ($posts as $post) : ?>
                    <li class="list-group-item">
                        <h5><?php echo $post['title']; ?></h5>
                        <p><?php echo $post['content']; ?></p>
                        <div class="btn-group" role="group">
                            <a href="post.php?edit=<?php echo $post['id']; ?>" class="btn btn-warning">Edit</a>
                            <form method="post" action="post.php" class="d-inline">
                                <input type="hidden" name="delete_post" value="<?php echo $post['id']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>You have no posts yet.</p>
        <?php endif; ?>

        <p class="mt-3"><a href="post.php">Add New Post</a></p>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
