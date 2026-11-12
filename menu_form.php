<?php
require "config.php";
require_admin();

// If there is an id in the URL, we are editing. If not, we are adding.
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$name = $category = $price = "";

if ($id > 0) {
    $stmt = $conn->prepare("SELECT name, category, price FROM menu_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
    if (!$item) { header("Location: dashboard.php"); exit; }
    $name = $item["name"];
    $category = $item["category"];
    $price = $item["price"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id       = (int)$_POST["id"];
    $name     = trim($_POST["name"]);
    $category = trim($_POST["category"]);
    $price    = $_POST["price"];

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE menu_items SET name = ?, category = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $name, $category, $price, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO menu_items (name, category, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $category, $price);
    }
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= $id ? "Edit" : "Add" ?> item | Kapehan</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="box">
    <h1><?= $id ? "Edit item" : "Add item" ?></h1>

    <form method="post">
      <input type="hidden" name="id" value="<?= $id ?>">

      <label>Name</label>
      <input type="text" name="name" value="<?= e($name) ?>" required>

      <label>Category</label>
      <input type="text" name="category" value="<?= e($category) ?>" required>

      <label>Price</label>
      <input type="number" name="price" step="0.01" min="0" value="<?= e($price) ?>" required>

      <button type="submit">Save</button>
    </form>
    <p><a href="dashboard.php">Cancel</a></p>
  </div>
</body>
</html>
