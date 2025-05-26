<?php
$body_class = "enhancements"; // Set the body class for this page
include("header.inc");
include("menu.inc");
require("settings.php");

// Database connection (optional for future use)
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    echo "<p>Database connection failed: " . mysqli_connect_error() . "</p>";
    exit();
}

// Get current time in Melbourne
$date_time = new DateTime('now', new DateTimeZone('Australia/Melbourne'));
$current_date_time = $date_time->format('g:i A T, l, F j, Y');
?>

<main>
    <section class="enhancements-header">
        <h1>Project Enhancements</h1>
        <p class="timestamp">Last updated: <?= $current_date_time; ?></p>
        <p class="description">This page outlines all team contributions that extend beyond the core project requirements.</p>
    </section>

    <?php
    $team_enhancements = [
        "Tran Minh Thien Phan" => [
            "Provide the Manager with the Ability to Sort EOI Records" => "Managers can now sort EOI records on <code>manage.php</code> by fields like <code>EOInumber</code>, <code>job_reference</code>, <code>first_name</code>, etc. A dropdown menu and dynamic SQL <code>ORDER BY</code> queries are used, with the sorting field passed via a <code>GET</code> parameter.",
            "Manager Registration Page with Server-Side Validation" => "The <code>register_manager.php</code> page allows manager sign-up with checks for unique username, email format, and strong passwords. Passwords are hashed using <code>password_hash()</code>.",
            "Authentication Protection for <code>manage.php</code>" => "Access to <code>manage.php</code> is restricted by session-based login. User credentials are verified using <code>password_verify()</code> against hashed database entries, redirecting unauthenticated users to <code>login.php</code>.",
            "Lockout After 3 Invalid Login Attempts" => "Invalid login attempts are tracked. After 3 failed tries in 15 minutes, the account is locked, showing a countdown until reactivation. Lockout logic uses the <code>invalid_attempts</code> and <code>last_attempt</code> columns in the database.",
        ],
        "Aungkita Chakma" => [
            "Responsive About Page Layout" => "Media queries were added to ensure that the About page displays well on phones and tablets, adapting the layout and profile cards to different screen sizes.",
            "Profile Card Hover Effects" => "Hover effects were added to the profile cards and images for better user engagement and interactivity.",
            "Accessibility Improvements" => "<code>title</code> attributes were added to navigation and footer links to improve usability for screen readers and visually impaired users.",
            "Meta Tags for SEO" => "Semantic <code>meta</code> tags like <code>author</code> and <code>description</code> were added to <code>header.inc</code> to improve SEO and search engine visibility.",
            "Button Hover Feedback on Apply Page" => "CSS enhancements were added to give hover feedback on the Apply page's Submit and Reset buttons, improving user experience.",
        ],
        "Nguyen Gia Bao Pham" => [
            "Manager Registration Page with Server-Side Validation" => "The <code>register_manager.php</code> page allows manager sign-up with checks for unique username, email format, and strong passwords. Passwords are hashed using <code>password_hash()</code>.",
            "Authentication Protection for <code>manage.php</code>" => "Access to <code>manage.php</code> is restricted by session-based login. User credentials are verified using <code>password_verify()</code> against hashed database entries, redirecting unauthenticated users to <code>login.php</code>.",
            "Lockout After 3 Invalid Login Attempts" => "Invalid login attempts are tracked. After 3 failed tries in 15 minutes, the account is locked, showing a countdown until reactivation. Lockout logic uses the <code>invalid_attempts</code> and <code>last_attempt</code> columns in the database.",
            "Provide the database with EOI table" => "Worked with phpMyAdmin to create and configure the <code>EOI</code> table schema.",
        ]
    ];

    foreach ($team_enhancements as $member => $enhancements) {
        echo "<section class='enhancements-list'>";
        echo "<h2>Enhancements by {$member}</h2>";
        $count = 1;
        foreach ($enhancements as $title => $description) {
            echo "<article class='enhancement-item'>";
            echo "<h3>{$count}. {$title}</h3>";
            echo "<p>{$description}</p>";
            echo "</article>";
            $count++;
        }
        echo "</section>";
    }
    ?>
</main>

<?php include("footer.inc"); ?>