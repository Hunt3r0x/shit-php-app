<?php
session_start();
require_once('db_connection.php');

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

$errors = [];

// Edit Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_post'])) {
    $editPostId = $_POST['edit_post'];
    $editPostTitle = $_POST['editPostTitle'];
    $editPostContent = $_POST['editPostContent'];

    $updateSql = "UPDATE posts SET title = '$editPostTitle', content = '$editPostContent' WHERE id = '$editPostId' AND user_id = '$user_id'";
    $updateResult = $connection->query($updateSql);

    if ($updateResult) {
        header("Location: posts.php");
        exit();
    } else {
        $errors[] = "Error editing post: " . $connection->error;
    }
}

// Delete Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post'])) {
    $deletePostId = $_POST['delete_post'];

    $deleteSql = "DELETE FROM posts WHERE id = '$deletePostId' AND user_id = '$user_id'";
    $deleteResult = $connection->query($deleteSql);

    if ($deleteResult) {
        header("Location: posts.php");
        exit();
    } else {
        $errors[] = "Error deleting post: " . $connection->error;
    }
}

// Add Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post'])) {
    $postTitle = $_POST['postTitle'];
    $postContent = $_POST['postContent'];

    $insertSql = "INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$postTitle', '$postContent')";
    $insertResult = $connection->query($insertSql);

    if ($insertResult) {
        header("Location: posts.php");
        exit();
    } else {
        $errors[] = "Error adding post: " . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit Post</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="text-center">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Add/Edit Post</h2>

            <?php
            if (!empty($errors)) {
                echo '<div class="alert alert-danger">';
                foreach ($errors as $error) {
                    echo "<p class='mb-0'>$error</p>";
                }
                echo '</div>';
            }
            ?>

            <form method="post" action="post.php">
                <?php
                // Display edit form if editing a post
                if (isset($_GET['edit'])) {
                    $editPostId = $_GET['edit'];
                    $editSql = "SELECT id, title, content FROM posts WHERE id = '$editPostId' AND user_id = '$user_id'";
                    $editResult = $connection->query($editSql);

                    if ($editResult && $editResult->num_rows > 0) {
                        $editPost = $editResult->fetch_assoc();
                ?>
                        <input type="hidden" name="edit_post" value="<?php echo $editPost['id']; ?>">
                        <div class="form-group">
                            <label for="editPostTitle">Post Title:</label>
                            <input type="text" class="form-control" name="editPostTitle" value="<?php echo $editPost['title']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="editPostContent">Post Content:</label>
                            <textarea class="form-control" name="editPostContent" rows="4" required><?php echo $editPost['content']; ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
                    <?php
                    }
                } else {
                    ?>
                    <!-- Add Post Form -->
                    <input type="hidden" name="post">
                    <div class="form-group">
                        <label for="postTitle">Post Title:</label>
                        <input type="text" class="form-control" name="postTitle" required>
                    </div>

                    <div class="form-group">
                        <label for="postContent">Post Content:</label>
                        <textarea class="form-control" name="postContent" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Add Post</button>
                <?php
                }
                ?>
            </form>

            <p class="mt-3"><a href="posts.php">View Your Posts</a></p>
            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>