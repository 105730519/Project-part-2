<?php 
session_start();
include 'header.inc'; 
include 'menu.inc';
require 'settings.php';

// Check if user is logged in
if (!isset($_SESSION['manager_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<h1>Error</h1><p>Database connection failed: " . mysqli_connect_error() . "</p><a href='manage.php'>Back to dashboard</a>");
}

// Check for lockout
$manager_id = $_SESSION['manager_id'];
$query = "SELECT lockout_until FROM managers WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $manager_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$current_time = new DateTime('now', new DateTimeZone('Australia/Melbourne'));
if ($user['lockout_until'] !== null) {
    $lockout_until = new DateTime($user['lockout_until']);
    if ($current_time < $lockout_until) {
        $remaining = $current_time->diff($lockout_until);
        $minutes = $remaining->i + ($remaining->h * 60);
        echo "<h1>Account Locked</h1><p>Your account is locked due to too many failed login attempts. Please try again in $minutes minutes.</p>";
        echo "<a href='login.php'>Back to login</a>";
        session_destroy();
        mysqli_close($conn);
        include 'footer.inc';
        exit();
    }
}

// Handle confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $eoi_number = $_POST['eoi_number'];
    $query = "DELETE FROM eoi WHERE EOInumber = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $eoi_number);
    if (mysqli_stmt_execute($stmt)) {
        echo "<h1>Deletion Successful</h1>";
        echo "<p>EOI #$eoi_number has been deleted.</p>";
        echo "<p><a href='manage.php'>Back to dashboard</a></p>";
    } else {
        echo "<h1>Error</h1><p>Failed to delete EOI: " . mysqli_error($conn) . "</p>";
        echo "<a href='manage.php'>Back to dashboard</a>";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    include 'footer.inc';
    exit();
}

// Get EOI details for confirmation
$eoi_number = isset($_GET['eoi_number']) ? (int)$_GET['eoi_number'] : 0;
if ($eoi_number) {
    $query = "SELECT first_name, last_name FROM eoi WHERE EOInumber = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $eoi_number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $eoi = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($eoi) {
?>
        <main>
            <h1>Confirm Deletion</h1>
            <p>Are you sure you want to delete the EOI for <?php echo htmlspecialchars($eoi['first_name'] . ' ' . $eoi['last_name']); ?> (EOI #<?php echo $eoi_number; ?>)?</p>
            <form action="confirm_delete.php" method="post">
                <input type="hidden" name="eoi_number" value="<?php echo $eoi_number; ?>">
                <input type="submit" name="confirm_delete" value="Yes, Delete">
                <a href="manage.php">No, Cancel</a>
            </form>
        </main>
<?php
    } else {
        echo "<h1>Error</h1><p>EOI not found.</p><a href='manage.php'>Back to dashboard</a>";
    }
} else {
    echo "<h1>Error</h1><p>Invalid EOI number.</p><a href='manage.php'>Back to dashboard</a>";
}

mysqli_close($conn);
include 'footer.inc';
?>
