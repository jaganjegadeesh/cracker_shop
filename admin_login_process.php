<?php
session_start();
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$result = $conn->query("SELECT * FROM admins WHERE username = '$username'");
$admin = $result->fetch_assoc();

if ($admin) {
    $_SESSION['admin'] = $username;
    header("Location: admin_dashboard.php");
} else {
    echo "Invalid login.";
}
?>
