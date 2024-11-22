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

require_once('db/db_config.php');

$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: login.php");
    exit();
}

if ($user['username'] === 'admin') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_data'])) {
        try {
            $connection->query("SET FOREIGN_KEY_CHECKS = 0;");

            $connection->query("TRUNCATE TABLE posts;");

            $connection->query("SET FOREIGN_KEY_CHECKS = 1;");

            echo "<p>All data deleted successfully.</p>";
        } catch (Exception $e) {
            echo "<p>Error deleting data: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
} else {
    echo "<p>Unauthorized action. Only admin can perform this action.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <?php if ($user['username'] === 'admin'): ?>
        <h1>Welcome Admin</h1>
        <form method="POST">
            <button type="submit" name="delete_data" onclick="return confirm('Are you sure you want to delete all database content? This action cannot be undone.');">Delete All Database Content</button>
        </form>
    <?php else: ?>
        <h1>Access Denied</h1>
    <?php endif; ?>
</body>
</html>
