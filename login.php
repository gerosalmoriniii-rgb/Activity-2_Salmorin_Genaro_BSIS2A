<?php
require "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, full_name, password_hash, role, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user || !password_verify($password, $user["password_hash"])) {
        $error = "Incorrect email or password.";
    } elseif ($user["status"] != "active") {
        $error = "Your account is deactivated.";
    } else {
        $_SESSION["user_id"]   = $user["id"];
        $_SESSION["full_name"] = $user["full_name"];
        $_SESSION["role"]      = $user["role"];
        header("Location: dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sign in | Kapehan</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="box">
    <h1>Kapehan - Sign in</h1>
    <?php if (isset($_GET["registered"])) echo "<p class='success'>Account created. Please sign in.</p>"; ?>
    <?php if ($error) echo "<p class='error'>" . e($error) . "</p>"; ?>

    <form method="post">
      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <button type="submit">Sign in</button>
    </form>
    <p>New here? <a href="register.php">Register</a></p>
  </div>
</body>
</html>
