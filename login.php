<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'header.inc';
include 'menu.inc';
require 'settings.php';

session_start();

// Database connection
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<h1>Error</h1><p>Database connection failed: " . mysqli_connect_error() . "</p><a href='login.php'>Back to login</a>");
}

// Function to sanitize input
function sanitize($data, $conn) {
    $data = trim($data);
    $data = strip_tags($data);
    return mysqli_real_escape_string($conn, $data);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '', $conn);
    $password = $_POST['password'] ?? '';

    // Check if user exists
    $query = "SELECT id, password, failed_attempts, lockout_until FROM managers WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        die("<h1>Error</h1><p>Query preparation failed: " . mysqli_error($conn) . "</p><a href='login.php'>Back to login</a>");
    }
    mysqli_stmt_bind_param($stmt, "s", $username);
    if (!mysqli_stmt_execute($stmt)) {
        die("<h1>Error</h1><p>Query execution failed: " . mysqli_error($conn) . "</p><a href='login.php'>Back to login</a>");
    }
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user) {
        // Check for lockout
        $current_time = new DateTime('now', new DateTimeZone('Australia/Melbourne'));
        if (isset($user['lockout_until']) && $user['lockout_until'] !== null) {
            $lockout_until = new DateTime($user['lockout_until']);
            if ($current_time < $lockout_until) {
                $remaining = $current_time->diff($lockout_until);
                $minutes = $remaining->i + ($remaining->h * 60);
                echo "<h1>Account Locked</h1><p>Your account is locked due to too many failed login attempts. Please try again in $minutes minutes.</p>";
                echo "<a href='login.php'>Back to login</a>";
                mysqli_close($conn);
                include 'footer.inc';
                exit();
            } else {
                // Reset failed attempts and lockout after lockout period expires
                $query = "UPDATE managers SET failed_attempts = 0, lockout_until = NULL WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $user['id']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Successful login: reset failed attempts
            $query = "UPDATE managers SET failed_attempts = 0, lockout_until = NULL WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $user['id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Set session variables
            $_SESSION['manager_id'] = $user['id'];
            $_SESSION['manager_username'] = $username;

            // Redirect to manage.php
            header('Location: manage.php');
            exit();
        } else {
            // Failed login: increment failed attempts
            $failed_attempts = (isset($user['failed_attempts']) ? $user['failed_attempts'] : 0) + 1;

            if ($failed_attempts >= 3) {
                // Lock account for 15 minutes
                $lockout_until = $current_time->add(new DateInterval('PT15M'))->format('Y-m-d H:i:s');
                $query = "UPDATE managers SET failed_attempts = ?, lockout_until = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "isi", $failed_attempts, $lockout_until, $user['id']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                echo "<h1>Account Locked</h1><p>Too many failed login attempts. Your account is locked for 15 minutes.</p>";
                echo "<a href='login.php'>Back to login</a>";
            } else {
                // Update failed attempts
                $query = "UPDATE managers SET failed_attempts = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ii", $failed_attempts, $user['id']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                echo "<h1>Login Failed</h1><p>Incorrect password. Attempt " . $failed_attempts . " of 3.</p>";
                echo "<a href='login.php'>Try again</a>";
            }
            mysqli_close($conn);
            include 'footer.inc';
            exit();
        }
    } else {
        echo "<h1>Login Failed</h1><p>Username not found.</p>";
        echo "<a href='login.php'>Try again</a>";
        mysqli_close($conn);
        include 'footer.inc';
        exit();
    }
}
?>

<main>
    <form action="login.php" method="post" class="form" novalidate="novalidate">
        <div class="apply-title">
            <h1>Manager Login</h1>
            <p>Fields marked with an <span class="required">*</span> are required</p>
        </div>

        <div class="Username">
            <p><label for="username">Username <span class="required">*</span></label></p>
            <p><input type="text" name="username" id="username" placeholder="Enter username" maxlength="50" required></p>
        </div>

        <div class="Password">
            <p><label for="password">Password <span class="required">*</span></label></p>
            <p><input type="password" name="password" id="password" placeholder="Enter password" required></p>
        </div>

        <div class="form-footer" role="group" aria-label="Form Actions">
            <input type="submit" value="Login" class="submit-button">
            <input type="reset" value="Reset">
        </div>
    </form>
</main>

<?php include 'footer.inc'; ?>