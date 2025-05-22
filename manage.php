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
    die("<h1>Error</h1><p>Database connection failed: " . mysqli_connect_error() . "</p><a href='login.php'>Back to login</a>");
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

// Handle queries and actions
$results = [];
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query_type = $_POST['query_type'] ?? '';
    $job_ref = trim($_POST['job_ref'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $eoi_number = trim($_POST['eoi_number'] ?? '');
    $new_status = $_POST['status'] ?? 'NEW';

    if ($query_type == 'all') {
        $result = mysqli_query($conn, "SELECT * FROM eoi ORDER BY EOInumber");
        $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } elseif ($query_type == 'job_ref' && !empty($job_ref)) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM eoi WHERE job_ref = ?");
        mysqli_stmt_bind_param($stmt, "s", $job_ref);
        mysqli_stmt_execute($stmt);
        $results = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
    } elseif ($query_type == 'applicant' && (!empty($first_name) || !empty($last_name))) {
        $where = [];
        if (!empty($first_name)) $where[] = "first_name LIKE '%" . mysqli_real_escape_string($conn, $first_name) . "%'";
        if (!empty($last_name)) $where[] = "last_name LIKE '%" . mysqli_real_escape_string($conn, $last_name) . "%'";
        $where_clause = implode(' AND ', $where);
        $result = mysqli_query($conn, "SELECT * FROM eoi WHERE $where_clause ORDER BY EOInumber");
        $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } elseif ($query_type == 'delete' && !empty($job_ref)) {
        $stmt = mysqli_prepare($conn, "DELETE FROM eoi WHERE job_ref = ?");
        mysqli_stmt_bind_param($stmt, "s", $job_ref);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $error = "EOIs with job reference $job_ref deleted.";
    } elseif ($query_type == 'update' && !empty($eoi_number)) {
        if (in_array($new_status, ['NEW', 'CURRENT', 'FINAL'])) {
            $stmt = mysqli_prepare($conn, "UPDATE eoi SET status = ? WHERE EOInumber = ?");
            mysqli_stmt_bind_param($stmt, "si", $new_status, $eoi_number);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $error = "Status for EOI #$eoi_number updated to $new_status.";
        }
    }
}

// Default fetch all if no filter applied
if (empty($results)) {
    $result = mysqli_query($conn, "SELECT * FROM eoi ORDER BY EOInumber");
    $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<main>
    <h1>Manager Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['manager_username']); ?>!</p>
    <p>Manage Expressions of Interest (EOIs) below.</p>

    <form method="post" action="manage.php">
        <label>Query Type:</label>
        <p><input type="radio" name="query_type" value="all" checked> List All EOIs</p>
        <p><input type="radio" name="query_type" value="job_ref"> List by Job Reference
        <input type="text" name="job_ref" placeholder="e.g., J001"></p>
        <p><input type="radio" name="query_type" value="applicant"> List by Applicant
        <input type="text" name="first_name" placeholder="First Name">
        <input type="text" name="last_name" placeholder="Last Name"></p>
        <p><input type="radio" name="query_type" value="delete"> Delete by Job Reference
        <input type="text" name="job_ref" placeholder="e.g., J001"></p>
        <p><input type="radio" name="query_type" value="update"> Update Status
        <input type="text" name="eoi_number" placeholder="EOI Number">
        <select name="status">
            <option value="NEW">NEW</option>
            <option value="CURRENT">CURRENT</option>
            <option value="FINAL">FINAL</option>
        </select></p>
        <input type="submit" value="Submit Query">
    </form>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <table border="1" style="border-collapse: collapse; width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>EOI Number</th>
                    <th>Job Ref</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Skills</th>
                    <th>Other Skills</th>
                    <th>Degree</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['EOInumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['job_ref']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['dob']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['street_address'] . ', ' . $row['suburb'] . ', ' . $row['state'] . ' ' . $row['postcode']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['skills'])); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['other_skills'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($row['degree'] ?? 'N/A'); ?></td>
                        <td>
                            <form action="manage.php" method="post" style="display: inline;">
                                <input type="hidden" name="query_type" value="update">
                                <input type="hidden" name="eoi_number" value="<?php echo $row['EOInumber']; ?>">
                                <select name="status">
                                    <option value="NEW" <?php echo $row['status'] === 'NEW' ? 'selected' : ''; ?>>NEW</option>
                                    <option value="CURRENT" <?php echo $row['status'] === 'CURRENT' ? 'selected' : ''; ?>>CURRENT</option>
                                    <option value="FINAL" <?php echo $row['status'] === 'FINAL' ? 'selected' : ''; ?>>FINAL</option>
                                </select>
                                <input type="submit" value="Update">
                            </form>
                        </td>
                        <td>
                            <a href="confirm_delete.php?eoi_number=<?php echo $row['EOInumber']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No EOIs found.</p>
    <?php endif; ?>

    <?php
    if ($_SESSION['manager_username'] === 'Nguyen Gia Bao Pham') {
        echo "<h2>Hi Bao the Manager</h2>";
        $query = "SELECT id, name, description FROM managers";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            echo '<table border="1" cellpadding="10"><tr><th>ID</th><th>Name</th><th>Description</th></tr>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['description']) . '</td></tr>';
            }
            echo '</table>';
        } else {
            echo "<p>No manager records found.</p>";
        }
    }
    ?>

    <p><a href="logout.php">Logout</a></p>
</main>

<?php
mysqli_close($conn);
