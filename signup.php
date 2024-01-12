<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <style>
        body {
            margin: 0px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .center-container {
            text-align: center;
        }
    </style>
</head>

<body>
    <div>
        <h1 class="center-container">Sign Up</h1>
        <div>
            <!-- Form for user registration -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <!-- Input for email -->
                <label for="email">Email</label><br />
                <input type="email" name="email" id="email" placeholder="Enter email" /><br /><br />
                <!-- Input for password -->
                <label for="password">Password</label><br />
                <input type="password" name="password" id="password" placeholder="Enter password" /><br /><br />

                <!-- Input for confirming password -->
                <label for="confirm-password">Confirm Password</label><br>
                <input type="password" name="confirm-password" id="confirm-password"
                    placeholder="Confirm password" /><br><br>

                <!-- Submit button for form -->
                <input type="submit" name="submit" value="Create Account" />
                <br /><br />
                <!-- Link to login page for existing users -->
                Already have an account? <a href="/html/login.html">Login</a>
            </form>
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
