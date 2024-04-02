<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: pages/files/myfiles.php");
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['signup'])) {
    require_once "connection.php";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $checkQuery = "SELECT * FROM users WHERE email = $1";
    $checkResult = pg_query_params($conn, $checkQuery, array($email));

    if (pg_num_rows($checkResult) > 0) {

        echo "<script>alert('Email already in use. Please choose a different email.');</script>";
    } elseif ($_POST['password'] !== $_POST['confirm-password']) {

        echo "<script>alert('Passwords do not match');</script>";
    } elseif (strlen($password) < 8) {

        echo "<script>alert('Password must be at least 8 characters long');</script>";
    } elseif (!empty($name) && !empty($email) && !empty($password)) {

        $insertQuery = "INSERT INTO users (name, email, password) VALUES ($1, $2, $3)";
        $insertResult = pg_query_params($conn, $insertQuery, array($name, $email, $password));

        if ($insertResult) {

            echo "<script>alert('Account created successfully. You can now login.'); window.location.href = 'login.php';</script>";
        } else {

            echo "<script>alert('Error creating account');</script>";
        }
    } else {

        echo "<script>alert('Please fill all the fields and provide a valid email');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style/signup.css">
    <title>Sign Up</title>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>Sign Up</h1>
            <div class="form-container">
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