<?php include 'includes/header.inc'; ?>
<?php include 'includes/menu.inc'; ?>

<main>
    <form action="https://mercury.swin.edu.au/it000000/formtest.php" method="post" class="form">
        <div class="apply-title">
            <h1>Job Application Form</h1>
            <p>Fields marked with an <span class="required">*</span> are required </p>
        </div>

        <div class="Name">
            <p><label for="first-name"> Name <span class="required">*</span></label></p>
            <p><input type="text" name="first_name" id="first-name" placeholder="First Name" maxlength="20" required></p>
            <p><input type="text" name="last_name" placeholder="Last Name" maxlength="20" required></p>
        </div>

        <div class="Birth">
            <p><label for="birth-date"> Birth Date  <span class="required">*</span></label></p>
            <p><input type="text" id="birth-date" placeholder="dd/mm/yyyy"></p>
        </div>

        <fieldset class="Gender">
            <legend> Gender <span class="required">*</span></legend>
            <p><label for="Male">Male</label></p>
            <p><input type="radio" id="Male" name="gender" value="Male"></p>
            <p><label for="Female">Female</label></p>
            <p><input type="radio" id="Female" name="gender" value="Female"></p>
        </fieldset>

        <div class="Address">
            <p><label for="Streetaddress"><b>Current Address</b> <span class="required">*</span></label></p>
            <p><input type="text" id="Streetaddress" placeholder="Street Address" maxlength="40" required="required"></p>
            <p><input type="text" id="Subrub/town" placeholder="Suburb/ Town" maxlength="40" required="required"></p>
            <p><label>State</label>
                <select name="state" id="state">
                    <option> Select your state</option>
                    <option value="Victoria">VIC</option>
                    <option value="NSW">NSW</option>
                    <option value="QLD">QLD</option>
                    <option value="NT">NT</option>
                    <option value="WA">WA</option>
                    <option value="SA">SA</option>
                    <option value="TAS">TAS</option>
                    <option value="ACT">ACT</option>
                </select>
            </p>
            <p><label for="postcode">Postcode</label></p>
            <p><input type="number" id="postcode" maxlength="4" min="1000" max="9999" placeholder="ex.1234"></p>
        </div>

        <div class="Information">
            Contact Information <span class="required">*</span>
            <p><input type="email" id="email" name="contactemail" placeholder="name@.gmail.com" required></p>
            <p><input type="tel" id="phone" name="phone" placeholder="(##) ####-####" pattern="\(\d{2}\) \d{4}-\d{4}"></p>
        </div>

        <div class="Qualification">
            Qualifications/ Certifications <span class="required">*</span>
            <p><label for="bachelors-degree-cs">Bachelor's degree in Computer Science</label>
                <input type="checkbox" id="bachelors-degree-cs" name="qualifications[]" value="Bachelor's degree in ComputerScience" required>
            </p>
            <!-- Add more qualifications -->
        </div>

        <div id="Other">
            <p><label for="other-skills">Other skills</label></p>
            <textarea name="other-skills" id="other-skills">Write description of your skills here...</textarea>
        </div>

        <div class="form-footer" role="group" aria-label="Form Actions">
            <input type="submit" value="Apply">
            <input type="reset" value="Reset">
        </div>
    </form>
</main>

<?php include 'includes/footer.inc'; ?>
