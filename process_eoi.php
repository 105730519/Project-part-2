<?php
include 'header.inc';
include 'menu.inc';
require 'settings.php';

// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: apply.php');
    exit();
}

// Database connection
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<h1>Error</h1><p>Database connection failed: " . mysqli_connect_error() . "</p><a href='apply.php'>Back to form</a>");
}

// Function to sanitize input
function sanitize($data, $conn) {
    $data = trim($data);
    $data = strip_tags($data);
    return mysqli_real_escape_string($conn, $data);
}

// Get and sanitize form data
$job_reference = sanitize($_POST['job_reference'] ?? '', $conn);
$first_name = sanitize($_POST['first_name'] ?? '', $conn);
$last_name = sanitize($_POST['last_name'] ?? '', $conn);
$date_of_birth = sanitize($_POST['date_of_birth'] ?? '', $conn);
$gender = sanitize($_POST['gender'] ?? '', $conn);
$street_address = sanitize($_POST['street_address'] ?? '', $conn);
$suburb_town = sanitize($_POST['suburb_town'] ?? '', $conn);
$state = sanitize($_POST['state'] ?? '', $conn);
$postcode = sanitize($_POST['postcode'] ?? '', $conn);
$email = sanitize($_POST['email_address'] ?? '', $conn);
$phone = sanitize($_POST['phone_number'] ?? '', $conn);
$skill1 = sanitize($_POST['skill1'] ?? '', $conn);
$skill2 = sanitize($_POST['skill2'] ?? '', $conn);
$skill3 = sanitize($_POST['skill3'] ?? '', $conn);
$other_skills = sanitize($_POST['other_skills'] ?? '', $conn);
$required_skills = isset($_POST['required_skills']) ? 1 : 0;
$degree = sanitize($_POST['degree'] ?? '', $conn);

// Combine skills into a single field (as a string with newlines)
$skills = implode("\n", array_filter([$skill1, $skill2, $skill3]));

// Server-side validation
$error = [];
if (empty($job_reference)) $error[] = "Job Reference is required.";
if (strlen($first_name) > 20) $error[] = "First Name must not exceed 20 characters.";
if (empty($first_name)) $error[] = "First Name is required.";
if (strlen($last_name) > 20) $error[] = "Last Name must not exceed 20 characters.";
if (empty($last_name)) $error[] = "Last Name is required.";
$date = DateTime::createFromFormat('d/m/Y', $date_of_birth);
if (!$date || $date->format('d/m/Y') !== $date_of_birth) $error[] = "Date of Birth must be in dd/mm/yyyy format and valid.";
if (!in_array($gender, ['M', 'F', 'Other'])) $error[] = "Invalid Gender.";
if (strlen($street_address) > 40) $error[] = "Street Address must not exceed 40 characters.";
if (empty($street_address)) $error[] = "Street Address is required.";
if (strlen($suburb_town) > 40) $error[] = "Suburb/Town must not exceed 40 characters.";
if (empty($suburb_town)) $error[] = "Suburb/Town is required.";
if (!in_array($state, ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'])) $error[] = "Invalid State.";
if (!preg_match("/^\d{4}$/", $postcode)) $error[] = "Postcode must be exactly 4 digits.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error[] = "Invalid email format.";
if (!preg_match("/^[0-9]{8,12}$/", $phone)) $error[] = "Phone Number must be 8 to 12 digits.";
if ($required_skills && empty($other_skills)) $error[] = "Other Skills is required if checkbox is selected.";

if (!empty($error)) {
    echo "<h1>Submission Error</h1><p>Please fix the following errors:</p><ul><li>" . implode("</li><li>", $error) . "</li></ul>";
    echo "<a href='apply.php'>Back to form</a>";
    mysqli_close($conn);
    include 'footer.inc';
    exit();
}

// Format date for database
$date_of_birth = $date->format('Y-m-d');

// Set degree to NULL if empty
$degree = empty($degree) ? NULL : $degree;

// Insert data into EOI table with corrected column names
$query = "INSERT INTO eoi (job_ref, first_name, last_name, dob, gender, street_address, suburb, state, postcode, email, phone, skills, other_skills, degree, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'NEW')";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssssssssssssss", $job_reference, $first_name, $last_name, $date_of_birth, $gender, $street_address, $suburb_town, $state, $postcode, $email, $phone, $skills, $other_skills, $degree);

if (mysqli_stmt_execute($stmt)) {
    $eoi_number = mysqli_insert_id($conn);
    $current_time = new DateTime('now', new DateTimeZone('Australia/Melbourne'));
    $formatted_time = $current_time->format('g:i A T, l, F j, Y'); // 11:30 PM AEST, Thursday, May 22, 2025
    echo "<h1>Submission Successful</h1>";
    echo "<p>Your Expression of Interest has been submitted at $formatted_time.</p>";
    echo "<p><strong>EOI Number:</strong> $eoi_number</p>";
    echo "<a href='apply.php'>Submit another EOI</a>";
} else {
    echo "<h1>Error</h1><p>Failed to submit EOI: " . mysqli_error($conn) . "</p>";
    echo "<a href='apply.php'>Back to form</a>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
include 'footer.inc';
?>