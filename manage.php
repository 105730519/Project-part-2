<?php
include 'settings.php';

// Database connection
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
$results = [];
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query_type = $_POST['query_type'] ?? '';
    $job_ref = trim($_POST['job_ref'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $eoi_number = trim($_POST['eoi_number'] ?? '');
    $new_status = $_POST['status'] ?? 'NEW';

    // List all EOIs
    if ($query_type == 'all') {
        $result = mysqli_query($conn, "SELECT * FROM eoi");
        $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    // List by job reference
    elseif ($query_type == 'job_ref' && !empty($job_ref)) {
        $result = mysqli_query($conn, "SELECT * FROM eoi WHERE job_ref = '$job_ref'");
        $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    // List by applicant name
    elseif ($query_type == 'applicant' && (!empty($first_name) || !empty($last_name))) {
        $where = [];
        if (!empty($first_name)) $where[] = "first_name LIKE '%$first_name%'";
        if (!empty($last_name)) $where[] = "last_name LIKE '%$last_name%'";
        $where_clause = implode(' AND ', $where);
        $result = mysqli_query($conn, "SELECT * FROM eoi WHERE $where_clause");
        $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    // Delete by job reference
    elseif ($query_type == 'delete' && !empty($job_ref)) {
        mysqli_query($conn, "DELETE FROM eoi WHERE job_ref = '$job_ref'");
        $error = "EOIs with job reference $job_ref deleted.";
    }
    // Update status
    elseif ($query_type == 'update' && !empty($eoi_number)) {
        mysqli_query($conn, "UPDATE eoi SET status = '$new_status' WHERE EOInumber = '$eoi_number'");
        $error = "Status for EOI #$eoi_number updated to $new_status.";
    }
}

// Fetch all for display if no specific query
if (empty($results)) {
    $result = mysqli_query($conn, "SELECT * FROM eoi");
    $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

mysqli_close($conn);
?>

<?php include 'header.inc'; ?>
<?php include 'menu.inc'; ?>
<h2>HR Manager - EOI Management</h2>

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
    <h3>EOI Results</h3>
    <table border="1">
        <tr>
            <th>EOI Number</th>
            <th>Job Ref</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Suburb</th>
            <th>State</th>
            <th>Postcode</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Other Skills</th>
            <th>Status</th>
            <th>Degree</th>
            <th>Skills</th>
        </tr>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?php echo $row['EOInumber']; ?></td>
                <td><?php echo $row['job_ref']; ?></td>
                <td><?php echo $row['first_name']; ?></td>
                <td><?php echo $row['last_name']; ?></td>
                <td><?php echo $row['dob']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['street_address']; ?></td>
                <td><?php echo $row['suburb']; ?></td>
                <td><?php echo $row['state']; ?></td>
                <td><?php echo $row['postcode']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['other_skills']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['degree']; ?></td>
                <td><?php echo $row['skills']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include 'footer.inc'; ?>