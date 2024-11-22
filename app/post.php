<?php
session_start();
require_once('db/db_connection.php');

// Secure session settings
session_set_cookie_params([
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_regenerate_id();

// Redirect if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$errors = [];

// Fetch user details
$stmt = $connection->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    header("Location: login.php");
    exit();
}
$stmt->close();

// Edit Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_post'])) {
    $editPostId = filter_var($_POST['edit_post'], FILTER_VALIDATE_INT);
    $editPostTitle = trim($_POST['editPostTitle']);
    $editPostContent = trim($_POST['editPostContent']);

    if ($editPostId && !empty($editPostTitle) && !empty($editPostContent)) {
        $stmt = $connection->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $editPostTitle, $editPostContent, $editPostId, $user_id);

        if ($stmt->execute()) {
            header("Location: posts.php");
            exit();
        } else {
            $errors[] = "Error editing post: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errors[] = "Invalid input provided.";
    }
}

// Delete Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post'])) {
    $deletePostId = filter_var($_POST['delete_post'], FILTER_VALIDATE_INT);

    if ($deletePostId) {
        $stmt = $connection->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $deletePostId, $user_id);

        if ($stmt->execute()) {
            header("Location: posts.php");
            exit();
        } else {
            $errors[] = "Error deleting post: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errors[] = "Invalid post ID.";
    }
}

// Add Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post'])) {
    $postTitle = trim($_POST['postTitle']);
    $postContent = trim($_POST['postContent']);

    if (!empty($postTitle) && !empty($postContent)) {
        $stmt = $connection->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $postTitle, $postContent);

        if ($stmt->execute()) {
            header("Location: posts.php");
            exit();
        } else {
            $errors[] = "Error adding post: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errors[] = "Title and content cannot be empty.";
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