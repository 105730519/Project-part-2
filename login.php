<?php
include("header.inc"); 
include("menu.inc"); 
require("settings.php");


// Database connection, if connection fails, display error and stop execution
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    echo "<p>Database connection failed: " . mysqli_connect_error() . "</p>";
    exit();
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT * FROM user WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['username'] = $username;
        header("Location: profile.php");
        exit;
    } else {
        echo "Invalid login credentials.";
    }
}
// HTML Login 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="container">
  <form class="form" method="POST">
    <h1 class="title">Sign In</h1>
    <p class="subtitle">Welcome back! Please enter your details</p>

    <label>Email</label>
    <input type="text" name="username" placeholder="Enter your email" required>

    <label>Password</label>
    <div class="password-wrapper">
      <input type="password" name="password" placeholder="••••••••" required>
      <span class="toggle">&#128065;</span>
    </div>

    <div class="options">
      <label><input type="checkbox"> Remember for 30 Days</label>
      <a href="#">Forgot password</a>
    </div>

    <input type="submit" value="Sign in" class="btn-primary">

    <div class="or">OR</div>

    <div class="social-buttons">
      <button type="button" class="google">Sign up with Google</button>
      <button type="button" class="facebook">Sign up with Facebook</button>
    </div>

    <p class="signup-text">Don't have an account? <a href="#">Sign up</a></p>
  </form>
</div>
</body>
</html>

<?php include("footer.inc"); ?>