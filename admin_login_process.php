<?php
session_start();
$conn = new mysqli("localhost", "root", "", "cracker_shop");

$username = $_POST['username'];
$password = $_POST['password'];

$result = $conn->query("SELECT * FROM admins WHERE username = '$username'");
$admin = $result->fetch_assoc();

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin'] = $username;
    header("Location: admin_dashboard.php");
} else {
    echo "Invalid login.";
}
?>
