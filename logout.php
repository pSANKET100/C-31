<?php
// Include the database connection
include "connection.php";

// Start the session
session_start();

// Destroy the session
session_destroy();

// Redirect to the login page after logout
header("Location:login.php");
exit();
?>