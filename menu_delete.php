<?php
require "config.php";
require_admin();

$id = (int)$_GET["id"];

$stmt = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: dashboard.php");
exit;
