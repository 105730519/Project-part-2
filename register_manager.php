<?php
include 'header.inc';
include 'menu.inc';
require 'settings.php';

// Database connection
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<h1>Error</h1><p>Database connection failed: " . mysqli_connect_error() . "</p><a href='register_manager.php'>Back to form</a>");
}

// Function to sanitize input
function sanitize($data, $conn) {
    $data = trim($data);
    $data = strip_tags($data);
    return mysqli_real_escape_string($conn, $data);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '', $conn);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Server-side validation
    $error = [];

    // Username validation
    if (empty($username)) {
        $error[] = "Username is required.";
    } elseif (strlen($username) > 50) {
        $error[] = "Username must not exceed 50 characters.";
    }

    // Check if username already exists
    $query = "SELECT COUNT(*) FROM managers WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    if ($count > 0) {
        $error[] = "Username already exists. Please choose a different one.";
    }

    // Password validation
    if (empty($password)) {
        $error[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $error[] = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/", $password)) {
        $error[] = "Password must contain at least one uppercase letter, one number, and one special character (!@#$%^&*).";
    } elseif ($password !== $confirm_password) {
        $error[] = "Passwords do not match.";
    }

    // If no errors, proceed with registration
    if (empty($error)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into managers table
        $query = "INSERT INTO managers (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            echo "<h1>Registration Successful</h1>";
            echo "<p>Manager account created for username: <strong>$username</strong>.</p>";
            echo "<p><a href='login.php'>Proceed to login</a></p>";
        } else {
            echo "<h1>Error</h1><p>Failed to register manager: " . mysqli_error($conn) . "</p>";
            echo "<a href='register_manager.php'>Back to form</a>";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        include 'footer.inc';
        exit();
    } else {
        echo "<h1>Registration Error</h1><p>Please fix the following errors:</p><ul><li>" . implode("</li><li>", $error) . "</li></ul>";
        echo "<a href='register_manager.php'>Back to form</a>";
        mysqli_close($conn);
        include 'footer.inc';
        exit();
    }
}
?>

<main>
    <form action="register_manager.php" method="post" class="form" novalidate="novalidate">
        <div class="apply-title">
            <h1>Manager Registration</h1>
            <p>Fields marked with an <span class="required">*</span> are required</p>
        </div>

        <div class="Username">
            <p><label for="username">Username <span class="required">*</span></label></p>
            <p><input type="text" name="username" id="username" placeholder="Enter username" maxlength="50" required></p>
        </div>

        <div class="Password">
            <p><label for="password">Password <span class="required">*</span></label></p>
            <p><input type="password" name="password" id="password" placeholder="Enter password" required></p>
            <p><small>Password must be at least 8 characters, with 1 uppercase letter, 1 number, and 1 special character (!@#$%^&*).</small></p>
        </div>

        <div class="ConfirmPassword">
            <p><label for="confirm_password">Confirm Password <span class="required">*</span></label></p>
            <p><input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required></p>
        </div>

        <div class="form-footer" role="group" aria-label="Form Actions">
            <input type="submit" value="Register" class="submit-button">
            <input type="reset" value="Reset">
        </div>
    </form>
</main>

<?php include 'footer.inc'; ?>