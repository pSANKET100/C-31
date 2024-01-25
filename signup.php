<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="signup.css">
    <title>Document</title>

</head>

<body>



    <div class="container">
        <div class="card">
            <h1>Sign Up</h1>
            <div class="form-container">
                <!-- Form for user login -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <!-- <label for="email">Email:</label> -->
                    <input type="email" id="email" name="email" placeholder="Enter email" required /><br /><br />
                    <!-- <label for="password">Password:</label> -->
                    <input type="password" id="password" name="password" placeholder="Enter password" required /><br /><br />
                    <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm password" required />
                    <br>
                    <input type="submit" name="login" value="Create Account" />
                </form>
            </div>
        </div>
    </div>
    <?php

    // Include database connection and validation functions
    include "connection.php";
    include "functions.php";

    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if the email is already in use
        $checkQuery = "SELECT * FROM users WHERE email = $1";
        $checkResult = pg_query_params($conn, $checkQuery, array($email));

        if (pg_num_rows($checkResult) > 0) {
            // Display alert if the email is already in use
    ?>
            <script>
                alert("Email already in use. Please choose a different email.");
            </script>
        <?php
        } elseif (!arePasswordsEqual($password, $_POST['confirm-password'])) {
            // Display alert if passwords do not match
        ?>
            <script>
                alert("Passwords do not match");
            </script>
        <?php
        } elseif (!passwordLength($password)) {
            // Display alert if password is less than 8 characters
        ?>
            <script>
                alert("Password must be at least 8 characters long");
            </script>
            <?php
        } elseif (!empty($email) && !empty($password) && isValidEmail($email)) {
            // Use prepared statement to prevent SQL injection
            $insertQuery = "INSERT INTO users (email, password) VALUES ($1, $2)";
            $insertResult = pg_query_params($conn, $insertQuery, array($email, $password));

            if ($insertResult) {
                // Display success message and redirect to login page
            ?>
                <script>
                    alert("Data inserted successfully");
                    window.location.href = "login.php"; // Corrected path
                </script>
            <?php
            } else {
                // Display error message if data insertion fails
            ?>
                <script>
                    alert("Error inserting data");
                </script>
            <?php
            }
        } else {
            // Display alert if form fields are not filled correctly
            ?>
            <script>
                alert("Please fill all the fields and provide a valid email");
            </script>
    <?php
        }
    }
    ?>
</body>

</html>