<?php
include("header.inc");
include("menu.inc");
require("settings.php");

// Database connection (for potential future use, e.g., displaying EOI sorting)
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    echo "<p>Database connection failed: " . mysqli_connect_error() . "</p>";
    exit();
}

// Current date and time
$date_time = new DateTime('now', new DateTimeZone('Australia/Melbourne'));
$current_date_time = $date_time->format('g:i A T, l, F j, Y'); // e.g., 9:07 AM AEST, Thursday, May 22, 2025
?>

<main>
    <section class="enhancements-header">
        <h1>Enhancements Overview</h1>
        <p>Last updated: <?php echo $current_date_time; ?></p>
        <p>This page documents the extra features contributed by team members that go beyond the base requirements of the project.</p>
    </section>

    <!-- === Enhancements by Tran Minh Thien Phan === -->
    <section class="enhancements-list">
        <h2>Enhancements by Tran Minh Thien Phan</h2>

        <article class="enhancement-item">
            <h3>1. Provide the Manager with the Ability to Sort EOI Records</h3>
            <p>Managers can now sort EOI records on <code>manage.php</code> by fields like <code>EOInumber</code>, <code>job_reference</code>, <code>first_name</code>, etc. A dropdown menu and dynamic SQL <code>ORDER BY</code> queries are used, with the sorting field passed via a <code>GET</code> parameter.</p>
        </article>

        <article class="enhancement-item">
            <h3>2. Manager Registration Page with Server-Side Validation</h3>
            <p>The <code>register_manager.php</code> page allows manager sign-up with checks for unique username, email format, and strong passwords. Passwords are hashed using <code>password_hash()</code>.</p>
        </article>

        <article class="enhancement-item">
            <h3>3. Authentication Protection for <code>manage.php</code></h3>
            <p>Access to <code>manage.php</code> is restricted by session-based login. User credentials are verified using <code>password_verify()</code> against hashed database entries, redirecting unauthenticated users to <code>login.php</code>.</p>
        </article>

        <article class="enhancement-item">
            <h3>4. Lockout After 3 Invalid Login Attempts</h3>
            <p>Invalid login attempts are tracked. After 3 failed tries in 15 minutes, the account is locked, showing a countdown until reactivation. Lockout logic uses the <code>invalid_attempts</code> and <code>last_attempt</code> columns in the database.</p>
        </article>
    </section>

    <!-- === Enhancements by Aungkita Chakma === -->
    <section class="enhancements-list">
        <h2>Enhancements by Aungkita Chakma</h2>

        <article class="enhancement-item">
            <h3>1. Responsive About Page Layout</h3>
            <p>Media queries were added to ensure that the About page displays well on phones and tablets, adapting the layout and profile cards to different screen sizes.</p>
        </article>

        <article class="enhancement-item">
            <h3>2. Profile Card Hover Effects</h3>
            <p>Hover effects were added to the profile cards and images for better user engagement and interactivity.</p>
        </article>

        <article class="enhancement-item">
            <h3>3. Accessibility Improvements</h3>
            <p><code>title</code> attributes were added to navigation and footer links to improve usability for screen readers and visually impaired users.</p>
        </article>

        <article class="enhancement-item">
            <h3>4. Meta Tags for SEO</h3>
            <p>Semantic <code>meta</code> tags like <code>author</code> and <code>description</code> were added to <code>header.inc</code> to improve SEO and search engine visibility.</p>
        </article>

        <article class="enhancement-item">
            <h3>5. Button Hover Feedback on Apply Page</h3>
            <p>CSS enhancements were added to give hover feedback on the Apply page's Submit and Reset buttons, improving user experience.</p>
        </article>
    </section>

    <!-- === Enhancements by Nguyen Gia Bao Pham === -->
    <section class="enhancements-list">
        <h2>Enhancements by Nguyen Gia Bao Pham</h2>

        <article class="enhancement-item">
            <h3>1. Provide the Manager with the Ability to Sort EOI Records</h3>
            <p>Managers can now sort EOI records on <code>manage.php</code> by fields like <code>EOInumber</code>, <code>job_reference</code>, <code>first_name</code>, etc. A dropdown menu and dynamic SQL <code>ORDER BY</code> queries are used, with the sorting field passed via a <code>GET</code> parameter.</p>
        </article>

        <article class="enhancement-item">
            <h3>2. Manager Registration Page with Server-Side Validation</h3>
            <p>The <code>register_manager.php</code> page allows manager sign-up with checks for unique username, email format, and strong passwords. Passwords are hashed using <code>password_hash()</code>.</p>
        </article>

        <article class="enhancement-item">
            <h3>3. Authentication Protection for <code>manage.php</code></h3>
            <p>Access to <code>manage.php</code> is restricted by session-based login. User credentials are verified using <code>password_verify()</code> against hashed database entries, redirecting unauthenticated users to <code>login.php</code>.</p>
        </article>

        <article class="enhancement-item">
            <h3>4. Lockout After 3 Invalid Login Attempts</h3>
            <p>Invalid login attempts are tracked. After 3 failed tries in 15 minutes, the account is locked, showing a countdown until reactivation. Lockout logic uses the <code>invalid_attempts</code> and <code>last_attempt</code> columns in the database.</p>
        </article>

        <article class="enhancement-item">
            <h3>5. Provide the database with EOI table</h3>
            <p>Work with the PHPMyAdmin for create a table</p>
        </article>
    </section>
</main>

<?php include("footer.inc"); ?>
