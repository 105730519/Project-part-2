<?php
include("includes/header.inc"); 
include("includes/menu.inc"); 
?>

<body class="about">
    <header class="company-name">
        <h1>Nhom Nhom<span class="color">.</span></h1>
        <?php include('menu.inc'); ?>
    </header>
    
    <h1 class="seng">About Our Group</h1>

    <h1>Group Information</h1>
    <table border="1">
        <caption>Group Information</caption>
        <tr>
            <th>Group name</th>
            <th>Class Time & Day</th>
        </tr>
        <tr>
            <td>Nhom Nhom</td>
            <td>Friday, 8.30</td>
        </tr>
    </table>

    <h2>Student IDs</h2>
    <ul class="student-ids">
        <li>Nguyen Gia Bao Pham
            <ul>
                <li class="right-align">ID: 105292789</li>
            </ul>
        </li>
        <li>Tran Minh Thien Phan
            <ul>
                <li class="right-align">ID: 105730519</li>
            </ul>
        </li>
        <li>Aungkita Chakma
            <ul>
                <li class="right-align">ID: 105704129</li>
            </ul>
        </li>
    </ul>

    <h1 class="bl">Additional Information</h1>
    <h2>Personal Profile</h2>
    <main>
        <section class="profile">
            <!-- Nguyen Gia Bao Pham -->
            <div class="profile-card">
                <div class="info">
                    <h2>Nguyen Gia Bao Pham</h2>
                    <h3>Personal Information</h3>
                    <p><strong>Name:</strong> Nguyen Gia Bao Pham</p>
                    <p><strong>Age:</strong> 18</p>
                    <p><strong>Gender:</strong> Male</p>
                    <p><strong>Major:</strong> Computer Science</p>
                    <p><strong>Student ID:</strong> 105292789</p>
                    <p><strong>Email:</strong> <a href="mailto:105292789@student.swin.edu.au">105292789@student.swin.edu.au</a></p>

                    <h3>Hometown: Hanoi</h3>
                    <p>Hanoi, the capital of Vietnam, is a city steeped in history...</p>

                    <h3>Interests</h3>
                    <ul>
                        <li><strong>Books:</strong> Diary Of a Whimpy Kid</li>
                        <li><strong>Music:</strong> RnB, Acoustic, Pop</li>
                        <li><strong>Films:</strong> Green Book</li>
                        <li><strong>Hobbies:</strong> Cooking, playing basketball</li>
                    </ul>
                </div>
                <div class="photo">
                    <img src="images/BaoPham.jpg" alt="Nguyen Gia Bao Pham">
                </div>
            </div>

            <!-- Tran Minh Thien Phan -->
            <div class="profile-card">
                <div class="info">
                    <h2>Tran Minh Thien Phan</h2>
                    <h3>Personal Information</h3>
                    <p><strong>Name:</strong> Tran Minh Thien Phan</p>
                    <p><strong>Age:</strong> 18</p>
                    <p><strong>Gender:</strong> Male</p>
                    <p><strong>Major:</strong> Computer Science</p>
                    <p><strong>Student ID:</strong> 105730519</p>
                    <p><strong>Email:</strong> <a href="mailto:105730519@student.swin.edu.au">105730519@student.swin.edu.au</a></p>

                    <h3>Hometown: Ho Chi Minh City</h3>
                    <p>Ho Chi Minh City, formerly known as Saigon, is the largest city...</p>

                    <h3>Interests</h3>
                    <ul>
                        <li><strong>Books:</strong> Love Hypothesis</li>
                        <li><strong>Music:</strong> Vpop</li>
                        <li><strong>Films:</strong> Lord of the Rings</li>
                        <li><strong>Hobbies:</strong> Games</li>
                    </ul>
                </div>
                <div class="photo">
                    <img src="images/ThienPhan.jpg" alt="Tran Minh Thien Phan">
                </div>
            </div>

            <!-- Aungkita Chakma -->
            <div class="profile-card">
                <div class="info">
                    <h2>Aungkita Chakma</h2>
                    <h3>Personal Information</h3>
                    <p><strong>Name:</strong> Aungkita Chakma</p>
                    <p><strong>Age:</strong> 19</p>
                    <p><strong>Gender:</strong> Female</p>
                    <p><strong>Major:</strong> Cyber Security</p>
                    <p><strong>Student ID:</strong> 105704129</p>
                    <p><strong>Email:</strong> <a href="mailto:105704129@student.swin.edu.au">105704129@student.swin.edu.au</a></p>

                    <h3>Hometown: Rangamati</h3>
                    <p>Rangamati is a scenic town known for its stunning landscapes...</p>

                    <h3>Interests</h3>
                    <ul>
                        <li><strong>Books:</strong> Cruel Prince, A Court of Thorns...</li>
                        <li><strong>Music:</strong> Pop and Kpop</li>
                        <li><strong>Films:</strong> Call Me By Your Name, Pride And Prejudice</li>
                        <li><strong>Hobbies:</strong> Sleeping and Playing Games</li>
                    </ul>
                </div>
                <div class="photo">
                    <img src="images/cat3.jpeg" alt="Aungkita Chakma">
                </div>
            </div>
        </section>
    </main>

    <h2>Tutor</h2>
    <p><strong>Tutor's Name:</strong> Razeen Hashmi</p>

    <h2>Members' Contributions</h2>
    <dl>
        <dt>Nguyen Gia Bao Pham</dt>
        <dd>Home page, CSS file and sections of job application page</dd>
        <dt>Tran Minh Thien Phan</dt>
        <dd>Home page, CSS file and sections of job application page</dd>
        <dt>Aungkita Chakma</dt>
        <dd>Home page, CSS file</dd>
    </dl>

    <?php include('footer.inc'); ?>
</body>
</html>