<?php
include("header.inc"); 
include("menu.inc"); 
?>

<main>
    <form action="process_eoi.php" method="post" class="form" novalidate="novalidate">
        <div class="apply-title">
            <h1>Job Application Form</h1>
            <p>Fields marked with an <span class="required">*</span> are required</p>
        </div>

        <div class="JobReference">
            <p><label for="job_reference">Job Reference Number <span class="required">*</span></label></p>
            <p>
                <select name="job_reference" id="job_reference" required>
                    <option value="">Select a Job</option>
                    <option value="J001">J001 - AI/ML Engineer (Junior Level)</option>
                    <option value="J002">J002 - AI/ML Engineer (Senior Level)</option>
                    <!-- Add more job references as needed -->
                </select>
            </p>
        </div>

        <div class="Name">
            <p><label for="first_name">Name <span class="required">*</span></label></p>
            <p><input type="text" name="first_name" id="first_name" placeholder="First Name" maxlength="20" required></p>
            <p><label for="last_name"></label><input type="text" name="last_name" id="last_name" placeholder="Last Name" maxlength="20" required></p>
        </div>

        <div class="Birth">
            <p><label for="date_of_birth">Birth Date <span class="required">*</span></label></p>
            <p><input type="text" id="date_of_birth" name="date_of_birth" placeholder="dd/mm/yyyy" required></p>
        </div>

        <fieldset class="Gender">
            <legend>Gender <span class="required">*</span></legend>
            <p><label><input type="radio" id="Male" name="gender" value="M" required> Male</label></p>
            <p><label><input type="radio" id="Female" name="gender" value="F"> Female</label></p>
            <p><label><input type="radio" id="Other" name="gender" value="Other"> Other</label></p>
        </fieldset>

        <div class="Address">
            <p><label for="street_address"><b>Current Address</b> <span class="required">*</span></label></p>
            <p><input type="text" id="street_address" name="street_address" placeholder="Street Address" maxlength="40" required></p>
            <p><input type="text" id="suburb_town" name="suburb_town" placeholder="Suburb/Town" maxlength="40" required></p>
            <p><label for="state">State <span class="required">*</span></label></p>
            <p>
                <select name="state" id="state" required>
                    <option value="">Select your state</option>
                    <option value="VIC">VIC</option>
                    <option value="NSW">NSW</option>
                    <option value="QLD">QLD</option>
                    <option value="NT">NT</option>
                    <option value="WA">WA</option>
                    <option value="SA">SA</option>
                    <option value="TAS">TAS</option>
                    <option value="ACT">ACT</option>
                </select>
            </p>
            <p><label for="postcode">Postcode <span class="required">*</span></label></p>
            <p><input type="text" id="postcode" name="postcode" maxlength="4" placeholder="ex. 1234" required></p>
        </div>

        <div class="Information">
            <p>Contact Information <span class="required">*</span></p>
            <p><label for="email_address">Email Address</label></p>
            <p><input type="email" id="email_address" name="email_address" placeholder="name@gmail.com" required></p>
            <p><label for="phone_number">Phone Number</label></p>
            <p><input type="tel" id="phone_number" name="phone_number" placeholder="0412345678" pattern="[0-9]{8,12}" maxlength="12" required></p>
        </div>

        <div class="Qualification">
            <p>Skills</p>
            <p><label for="skill1">Skill 1</label></p>
            <p><input type="text" id="skill1" name="skill1" maxlength="50"></p>
            <p><label for="skill2">Skill 2</label></p>
            <p><input type="text" id="skill2" name="skill2" maxlength="50"></p>
            <p><label for="skill3">Skill 3</label></p>
            <p><input type="text" id="skill3" name="skill3" maxlength="50"></p>
            <p><label for="required_skills">Required Technical Skills</label></p>
            <p><input type="checkbox" id="required_skills" name="required_skills"></p>
        </div>

        <div id="Other">
            <p><label for="other_skills">Other Skills</label></p>
            <textarea name="other_skills" id="other_skills" rows="4" cols="50" placeholder="Descriptions of your skills here..."></textarea>
        </div>

        <div class="Degree">
            <p><label for="degree">Degree (Optional)</label></p>
            <p><input type="text" name="degree" id="degree" placeholder="e.g., BSc Computer Science" maxlength="100"></p>
        </div>

        <div class="form-footer" role="group" aria-label="Form Actions">
            <input type="submit" value="Apply" class="submit-button">
            <input type="reset" value="Reset">
        </div>
    </form>
</main>

<?php include("footer.inc"); ?>
