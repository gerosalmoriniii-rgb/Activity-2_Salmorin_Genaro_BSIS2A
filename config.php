<?php
session_start();

$conn = new mysqli("localhost", "root", "", "kapehan_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Safely print text on the page
function e($text) {
    return htmlspecialchars($text);
}

// Access control
function is_admin() {
    return isset($_SESSION["role"]) && $_SESSION["role"] == "admin";
}

function require_login() {
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
}

function require_admin() {
    require_login();
    if (!is_admin()) {
        die("Access denied. Admins only. <a href='dashboard.php'>Back</a>");
    }
}
