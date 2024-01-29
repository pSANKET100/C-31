<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <a href="dashboard.php">login</a>
</head>
<body>
    <div>
    <h1>Dashboard</h1>
    <div>
    <form action="">
        <label for="inputfile">Select file</label>
        <input type="file" name="inputfile" id="">
    </form>
</div>
</div>
<?php
include "connection.php";
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Destroy the session
    session_destroy();

    // Redirect to the login page after logout
    header("Location: login.php");
    exit();
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}
?>

</body>
</html>