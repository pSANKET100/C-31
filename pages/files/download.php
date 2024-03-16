<?php
session_start();

// Check if the user is not logged in, redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include the database connection
include_once "../../connection.php";

// Function to download the file
function downloadFile($conn, $file_id, $table)
{
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Query to fetch the file details
    $query = "SELECT file_name, file_path FROM $table WHERE fileid = $1 AND userid = $2";
    $result = pg_query_params($conn, $query, array($file_id, $user_id));

    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $file_name = $row['file_name'];
        $file_path = $row['file_path'];

        // Check if the file exists
        if (file_exists($file_path)) {
            // Set the content type header for file download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header('Content-Length: ' . filesize($file_path));

            // Read the file and send it to the browser
            readfile($file_path);
            exit;
        } else {
            echo "File not found.";
        }
    } else {
        echo "File not found or you don't have permission to access it.";
    }
}

// Check if the file ID and table name are provided
if (isset($_GET['file_id']) && isset($_GET['table'])) {
    $file_id = $_GET['file_id'];
    $table = $_GET['table'];
    downloadFile($conn, $file_id, $table);
} else {
    echo "File ID or table name is missing.";
}
