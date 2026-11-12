<?php
require "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["full_name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm_password"];

    // Check if the email is already used
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($name == "" || $email == "" || $password == "") {
        $error = "Please fill in all fields.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif ($password != $confirm) {
        $error = "Passwords do not match.";
    } elseif ($stmt->num_rows > 0) {
        $error = "That email is already registered.";
    } else {
        // First account is the admin, the rest are customers
        $count = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()["total"];
        $role = ($count == 0) ? "admin" : "customer";

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hash, $role);
        $stmt->execute();

        header("Location: login.php?registered=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register | Kapehan</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="box">
    <h1>Kapehan - Register</h1>
    <?php if ($error) echo "<p class='error'>" . e($error) . "</p>"; ?>

    <form method="post">
      <label>Full name</label>
      <input type="text" name="full_name" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <label>Confirm password</label>
      <input type="password" name="confirm_password" required>

      <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Sign in</a></p>
  </div>
</body>
</html>
