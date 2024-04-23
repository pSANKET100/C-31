<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

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
    <style>
        h2 {
            text-align: center;
        }

        /* .new {
            margin: 0 5px;
            background: #fff;
            font-size: 18px;
            padding: 10px 15px;
            border-radius: 6px;
            border: 3px solid #000;
            box-shadow: 5px 5px 0px 0px #000;
            cursor: pointer;
        } */
    </style>
</head>

<body>
    <br>
    <?php

    // Check if any of the buttons were clicked
    if (isset($_POST['encrypted'])) {
        echo "<h2>Encrypted Files</h2>";
        displayFiles($conn, "encrypted_files");

    } elseif (isset($_POST['decrypted'])) {
        echo "<h2>Decrypted Files</h2>";
        displayFiles($conn, "decrypted_files");

    } elseif (isset($_POST['externally_encrypted_files'])) {
        echo "<h2>Externally Encrypted Files</h2>";
        displayFiles($conn, "externally_encrypted_files");

    } else {
        // Default option: Display all files
        echo "<h2>All Files</h2>";
        displayFiles($conn, "files");
    }
    ?>


</body>

</html>