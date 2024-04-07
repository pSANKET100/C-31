<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

include_once "../../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_file"])) {
    $selected_file_id = $_POST["file_id"];
    $table_name = $_POST["table_name"];

    $user_id = $_SESSION['user_id'];

    $delete_query = "DELETE FROM $table_name WHERE fileid = $1 AND userid = $2";
    $delete_result = pg_query_params($conn, $delete_query, array($selected_file_id, $user_id));

    if ($delete_result) {
        echo "<script>alert('File deleted successfully.'); window.location.href = 'myfiles.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error: Failed to delete file.'); window.location.href = 'myfiles.php';</script>";
        exit;
    }
} else {
    header("Location: myfiles.php");
    exit;
}
