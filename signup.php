<?php
session_start(); // Start the session

// Check if the user is already logged in, redirect to dashboard if logged in
if (isset($_SESSION['user_id'])) {
    header("Location: fileuploadtest/index.php");
    exit;
}

// Enable error reporting for debugging purposes (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form submission occurred
if (isset($_POST['signup'])) { // Corrected the button name to match the form
    // Include database connection
    require_once "connection.php";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email is already in use
    $checkQuery = "SELECT * FROM users WHERE email = $1";
    $checkResult = pg_query_params($conn, $checkQuery, array($email));

    if (pg_num_rows($checkResult) > 0) {
        // Display alert if the email is already in use
        echo "<script>alert('Email already in use. Please choose a different email.');</script>";
    } elseif ($_POST['password'] !== $_POST['confirm-password']) {
        // Display alert if passwords do not match
        echo "<script>alert('Passwords do not match');</script>";
    } elseif (strlen($password) < 8) {
        // Display alert if password is less than 8 characters
        echo "<script>alert('Password must be at least 8 characters long');</script>";
    } elseif (!empty($name) && !empty($email) && !empty($password)) {
        // Use prepared statement to prevent SQL injection
        $insertQuery = "INSERT INTO users (name, email, password) VALUES ($1, $2, $3)";
        $insertResult = pg_query_params($conn, $insertQuery, array($name, $email, $password));

        if ($insertResult) {
            // Display success message and redirect to login page
            echo "<script>alert('Account created successfully. You can now login.'); window.location.href = 'login.php';</script>";
        } else {
            // Display error message if data insertion fails
            echo "<script>alert('Error creating account');</script>";
        }
    } else {
        // Display alert if form fields are not filled correctly
        echo "<script>alert('Please fill all the fields and provide a valid email');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="signup.css">
    <title>Sign Up</title>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>Sign Up</h1>
            <div class="form-container">
                <!-- Form for user signup -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="text" id="name" name="name" placeholder="Enter name" required /><br /><br />
                    <input type="email" id="email" name="email" placeholder="Enter email" required /><br /><br />
                    <input type="password" id="password" name="password" placeholder="Enter password"
                        required /><br /><br />
                    <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm password"
                        required /><br /><br />
                    <input type="submit" name="signup" value="Create Account" />
                </form>
            </div>
        </div>
    </div>
</body>

</html>