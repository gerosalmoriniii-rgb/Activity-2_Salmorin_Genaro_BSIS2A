<?php
require "config.php";
require_login();

$result = $conn->query("SELECT * FROM menu_items ORDER BY category, name");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Menu | Kapehan</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="box wide">
    <p>
      Hello, <?= e($_SESSION["full_name"]) ?> (<?= e($_SESSION["role"]) ?>) |
      <a href="dashboard.php">Menu</a>
      <?php if (is_admin()) echo "| <a href='users.php'>Users</a>"; ?>
      | <a href="logout.php">Sign out</a>
    </p>

    <h1>Menu</h1>
    <?php if (is_admin()) echo "<p><a href='menu_form.php'>+ Add item</a></p>"; ?>

    <table>
      <tr>
        <th>No.</th><th>Name</th><th>Category</th><th>Price</th>
        <?php if (is_admin()) echo "<th>Actions</th>"; ?>
      </tr>
      <?php
      $no = 1;
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $no++ . "</td>";
          echo "<td>" . e($row["name"]) . "</td>";
          echo "<td>" . e($row["category"]) . "</td>";
          echo "<td>" . number_format($row["price"], 2) . "</td>";
          if (is_admin()) {
              echo "<td>
                      <a href='menu_form.php?id=" . $row["id"] . "'>Edit</a> |
                      <a href='menu_delete.php?id=" . $row["id"] . "' onclick=\"return confirm('Delete this item?');\">Delete</a>
                    </td>";
          }
          echo "</tr>";
      }
      ?>
    </table>
  </div>
</body>
</html>
