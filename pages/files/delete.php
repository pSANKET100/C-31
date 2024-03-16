<?php
session_start(); // Start the session

// Check if the user is not logged in, redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

// Include database connection
include_once "../../connection.php";

// Check if the form for deleting a file is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_file"])) {
    // Get the selected file ID and table name from the form
    $selected_file_id = $_POST["file_id"];
    $table_name = $_POST["table_name"]; // Retrieve the table name

    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Construct the DELETE query using the table name
    $delete_query = "DELETE FROM $table_name WHERE fileid = $1 AND userid = $2";
    $delete_result = pg_query_params($conn, $delete_query, array($selected_file_id, $user_id));

    if ($delete_result) {
        // File deleted successfully
        echo "<script>alert('File deleted successfully.'); window.location.href = 'myfiles.php';</script>";
        exit;
    } else {
        // Error occurred while deleting file
        echo "<script>alert('Error: Failed to delete file.'); window.location.href = 'myfiles.php';</script>";
        exit;
    }
} else {
    // If the form is not submitted, redirect to myfiles.php
    header("Location: myfiles.php");
    exit;
}
