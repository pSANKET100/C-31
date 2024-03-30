<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if the user is not logged in, redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

// Include the database connection
include_once "../../connection.php";
include_once '../../fileuploadtest/includes/function.php';
include '../../navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Files</title>
    <link rel="stylesheet" href="../../style/myfiles.css">
</head>

<body>
    <br><br>
    <!-- <a href="../../fileuploadtest/index.php"><button>Home</button></a> -->
    <h2>Files</h2>
    <?php displayFiles($conn, "files"); ?>
    <hr>
    <h2>Encrypted Files</h2>
    <?php displayFiles($conn, "encrypted_files"); ?><br><br>
    <hr>
    <h2>Decrypted Files</h2>
    <?php displayFiles($conn, "decrypted_files"); ?><br><br>
    <hr>
    <h2>Externally Encrypted Files</h2>
    <?php displayFiles($conn, "externally_encrypted_files"); ?><br><br>
    <hr>
   
    <br>
</body>

</html>