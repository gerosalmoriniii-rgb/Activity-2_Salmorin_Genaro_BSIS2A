<?php
require "config.php";
require_admin();

// Handle actions like users.php?action=delete&id=3
if (isset($_GET["action"]) && isset($_GET["id"])) {
    $id = (int)$_GET["id"];
    $action = $_GET["action"];

    $queries = [
        "make_admin"    => "UPDATE users SET role = 'admin' WHERE id = ?",
        "make_customer" => "UPDATE users SET role = 'customer' WHERE id = ?",
        "activate"      => "UPDATE users SET status = 'active' WHERE id = ?",
        "deactivate"    => "UPDATE users SET status = 'inactive' WHERE id = ?",
        "delete"        => "DELETE FROM users WHERE id = ?"
    ];

    // Admins cannot change their own account
    if ($id != $_SESSION["user_id"] && isset($queries[$action])) {
        $stmt = $conn->prepare($queries[$action]);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    header("Location: users.php");
    exit;
}

$result = $conn->query("SELECT id, full_name, email, role, status FROM users ORDER BY id");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Users | Kapehan</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="box wide">
    <p>
      Hello, <?= e($_SESSION["full_name"]) ?> (<?= e($_SESSION["role"]) ?>) |
      <a href="dashboard.php">Menu</a> | <a href="users.php">Users</a> |
      <a href="logout.php">Sign out</a>
    </p>

    <h1>User management</h1>

    <table>
      <tr><th>No.</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr>
      <?php
      $no = 1;
      while ($u = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $no++ . "</td>";
          echo "<td>" . e($u["full_name"]) . "</td>";
          echo "<td>" . e($u["email"]) . "</td>";
          echo "<td>" . e($u["role"]) . "</td>";
          echo "<td>" . e($u["status"]) . "</td>";
          echo "<td>";
          if ($u["id"] == $_SESSION["user_id"]) {
              echo "(you)";
          } else {
              $id = $u["id"];
              $roleAction   = ($u["role"] == "admin") ? "make_customer" : "make_admin";
              $roleLabel    = ($u["role"] == "admin") ? "Make customer" : "Make admin";
              $statusAction = ($u["status"] == "active") ? "deactivate" : "activate";
              $statusLabel  = ($u["status"] == "active") ? "Deactivate" : "Activate";

              echo "<a href='users.php?action=$roleAction&id=$id'>$roleLabel</a> | ";
              echo "<a href='users.php?action=$statusAction&id=$id'>$statusLabel</a> | ";
              echo "<a href='users.php?action=delete&id=$id' onclick=\"return confirm('Delete this user?');\">Delete</a>";
          }
          echo "</td>";
          echo "</tr>";
      }
      ?>
    </table>
  </div>
</body>
</html>
