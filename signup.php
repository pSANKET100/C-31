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
    if (isset($_POST['login'])) { // Corrected the button name to match the form
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if the email is already in use
        $checkQuery = "SELECT * FROM users WHERE email = $1";
        $checkResult = pg_query_params($conn, $checkQuery, array($email));

        if (pg_num_rows($checkResult) > 0) {
            // Display alert if the email is already in use
            echo "<script>alert('Email already in use. Please choose a different email.');</script>";
        } elseif (!arePasswordsEqual($password, $_POST['confirm-password'])) {
            // Display alert if passwords do not match
            echo "<script>alert('Passwords do not match');</script>";
        } elseif (!passwordLength($password)) {
            // Display alert if password is less than 8 characters
            echo "<script>alert('Password must be at least 8 characters long');</script>";
        } elseif (!empty($email) && !empty($password) && isValidEmail($email)) {
            // Use prepared statement to prevent SQL injection
            $insertQuery = "INSERT INTO users (email, password) VALUES ($1, $2)";
            $insertResult = pg_query_params($conn, $insertQuery, array($email, $password));

            if ($insertResult) {
                // Display success message and redirect to login page
                echo "<script>alert('Data inserted successfully'); window.location.href = 'login.php';</script>";
            } else {
                // Display error message if data insertion fails
                echo "<script>alert('Error inserting data');</script>";
            }
        } else {
            // Display alert if form fields are not filled correctly
            echo "<script>alert('Please fill all the fields and provide a valid email');</script>";
        }
    }
    ?>

</body>

</html>