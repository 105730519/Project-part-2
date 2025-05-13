<?php
// Include database settings
include 'settings.php';

// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: apply.php');
    exit();
}

// Database connection
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<html><body><h1>Error</h1><p>Database connection failed.</p><a href='apply.php'>Back to form</a></body></html>");
}

//EOI table link

if (!mysqli_query($conn, $create_table)) {
    die("<html><body><h1>Error</h1><p>Failed to create table.</p><a href='apply.php'>Back to form</a></body></html>");
}

// Get and sanitize form data
$job_reference = isset($_POST['job_reference']) ? trim(strip_tags($_POST['job_reference'])) : '';
$first_name = isset($_POST['first_name']) ? trim(strip_tags($_POST['first_name'])) : '';
$last_name = isset($_POST['last_name']) ? trim(strip_tags($_POST['last_name'])) : '';
$street_address = isset($_POST['street_address']) ? trim(strip_tags($_POST['street_address'])) : '';
$suburb_town = isset($_POST['suburb_town']) ? trim(strip_tags($_POST['suburb_town'])) : '';
$state = isset($_POST['state']) ? trim(strip_tags($_POST['state'])) : '';
$postcode = isset($_POST['postcode']) ? trim(strip_tags($_POST['postcode'])) : '';
$email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
$phone = isset($_POST['phone']) ? trim(strip_tags($_POST['phone'])) : '';
$skill1 = isset($_POST['skill1']) ? trim(strip_tags($_POST['skill1'])) : '';
$skill2 = isset($_POST['skill2']) ? trim(strip_tags($_POST['skill2'])) : '';
$skill3 = isset($_POST['skill3']) ? trim(strip_tags($_POST['skill3'])) : '';
$other_skills = isset($_POST['other_skills']) ? trim(strip_tags($_POST['other_skills'])) : '';

// Basic validation: check required fields
$error = '';
if (empty($job_reference)) $error .= "<li>Job Reference Number is required.</li>";
if (empty($first_name)) $error .= "<li>First Name is required.</li>";
if (empty($last_name)) $error .= "<li>Last Name is required.</li>";
if (empty($street_address)) $error .= "<li>Street Address is required.</li>";
if (empty($suburb_town)) $error .= "<li>Suburb/Town is required.</li>";
if (empty($state)) $error .= "<li>State is required.</li>";
if (empty($postcode)) $error .= "<li>Postcode is required.</li>";
if (empty($email)) $error .= "<li>Email Address is required.</li>";
if (empty($phone)) $error .= "<li>Phone Number is required.</li>";

// Check if there are errors
if ($error != '') {
    echo "<html><body><h1>Submission Error</h1><p>Please fix the following errors:</p><ul>$error</ul>";
    echo "<a href='apply.php'>Back to form</a></body></html>";
    mysqli_close($conn);
    exit();
}

// Insert data into EOI table
$query = "INSERT INTO eoi (
    job_reference_number, first_name, last_name, street_address, suburb_town, state, postcode,
    email_address, phone_number, skill1, skill2, skill3, other_skills, status
) VALUES (
    '$job_reference', '$first_name', '$last_name', '$street_address', '$suburb_town', '$state', '$postcode',
    '$email', '$phone', '$skill1', '$skill2', '$skill3', '$other_skills', 'New'
)";
if (mysqli_query($conn, $query)) {
    $eoi_number = mysqli_insert_id($conn);
    echo "<html><body><h1>Submission Successful</h1>";
    echo "<p>Your Expression of Interest has been submitted.</p>";
    echo "<p><strong>EOI Number:</strong> $eoi_number</p>";
    echo "<a href='apply.php'>Submit another EOI</a></body></html>";
} else {
    echo "<html><body><h1>Error</h1><p>Failed to submit EOI. Please try again.</p>";
    echo "<a href='apply.php'>Back to form</a></body></html>";
}

// Close connection
mysqli_close($conn);
?>