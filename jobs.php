<?php
include("header.inc"); 
include("menu.inc"); 
require("settings.php");

// Database connection
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    echo "<p>Database connection failed: " . mysqli_connect_error() . "</p>";
    exit();
}

// Fetch all jobs from the database
$query = "SELECT * FROM job ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "<p>Error fetching job: " . mysqli_error($conn) . "</p>";
    mysqli_close($conn);
    exit();
}

$jobs = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

// Current date and time
$date_time = new DateTime('now', new DateTimeZone('Australia/Melbourne'));
$current_date_time = $date_time->format('g:i A T, l, F j, Y'); // e.g., 5:55 PM AEST, Friday, May 16, 2025
?>

<main>
    <section class="jobs-header">
        <h1>Job Descriptions</h1>
        <p>Last updated: <?php echo $current_date_time; ?></p>
    </section>

    <section class="jobs-list">
        <div class="jobs-h2">
            <h2>Current Openings</h2>
        </div>

        <?php if (empty($jobs)): ?>
            <p>No job openings available at the moment. Please check back later.</p>
        <?php else: ?>
            <?php foreach ($jobs as $job): ?>
                <section>
                    <div class="div-current-openings">
                        <h3><?php echo htmlspecialchars($job['job_title']); ?> (Ref: <?php echo htmlspecialchars($job['job_reference']); ?>)</h3>
                        <p><strong>Location:</strong> Hawthorn</p>
                        <p><strong>Job Type:</strong> <?php echo htmlspecialchars($job['type']); ?></p>
                        <p><strong>Department:</strong> Information Technology / Artificial Intelligence</p>
                        <h4>Job Overview</h4>
                        <p><?php echo nl2br(htmlspecialchars($job['job_overview'])); ?></p>
                        <h4>Experience Level:</h4>
                        <?php
                        $experience = strpos($job['essential_skill'], '0-2 years') !== false ? '0-2 years' : '5+ years';
                        echo "<p>$experience</p>";
                        ?>
                        <h4>Salary:</h4>
                        <p class="salary-estimate"><?php echo htmlspecialchars($job['salary']); ?></p>
                        <h4>Key Responsibilities:</h4>
                        <ul>
                            <?php
                            $responsibilities = explode("\n", $job['key_responsibility']);
                            foreach ($responsibilities as $resp) {
                                if (trim($resp) !== '') {
                                    echo "<li>" . htmlspecialchars(trim($resp)) . "</li>";
                                }
                            }
                            ?>
                        </ul>
                        <h4>Qualifications & Skills:</h4>
                        <ul>
                            <?php
                            $skills = array_merge(explode("\n", $job['essential_skill']), explode("\n", $job['prefer_skill']));
                            foreach ($skills as $skill) {
                                if (trim($skill) !== '') {
                                    echo "<li>" . htmlspecialchars(trim($skill)) . "</li>";
                                }
                            }
                            ?>
                        </ul>
                        <p><strong>Reports To:</strong> <?php echo htmlspecialchars($job['Reports_To']); ?></p>
                        <a href="apply.php?job_ref=<?php echo urlencode($job['job_reference']); ?>" class="btn">Apply Now</a>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <aside>
        <div class="location">
            <h3>Location</h3>
            <p>Most positions are based in Hawthorn, VIC. View on map:</p>
            <a href="https://maps.google.com/?q=Hawthorn,+VIC,+Australia" target="_blank">View on Google Maps</a>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835234569423!2d145.03473261579885!3d-37.82373897989172!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0x5045675218ce7e0!2sHawthorn%2C+VIC%2C+Australia!5e0!3m2!1sen!2sus!4v1623456789!5m2!1sen!2sus" class="map-embed" allowfullscreen loading="lazy"></iframe>
        </div>
    </aside>

    <aside class="faq-section">
        <h3>FAQ</h3>
        <ul>
            <li><strong>What are the working hours?</strong> Typically 9am to 5pm AEST.</li>
            <li><strong>Is there parking available?</strong> Limited on-site parking is available.</li>
            <li><strong>What is the team size?</strong> Around 10 engineers in the AI/ML team.</li>
        </ul>
    </aside>
</main>

<?php include("footer.inc"); ?>