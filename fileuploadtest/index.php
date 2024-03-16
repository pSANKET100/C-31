<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Start the session

// Check if the user is not logged in, redirect to login page if not logged in
if (!isset ($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

// Include database connection
include_once "includes/connection.php";

// Check if the form for downloading a file is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["download_file"])) {
  // Get the selected file ID from the form
  $selected_file_id = $_POST["file_id"];

  // Redirect to another PHP file to process the download
  header("Location: download.php?file_id=$selected_file_id");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Files</title>
</head>

<body>
  <h1>Welcome,
    <?php echo isset ($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>
  </h1>
  <!-- <form action="upload.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload" />
    <input type="submit" value="Upload File" name="submit" />
  </form>
  <br>
  <form action="upload.php" method="post">
  </form> -->
  <br><br>
  <a href="../pages/files/myfiles.php">
    <button>My Files</button>
  </a>
  &nbsp;&nbsp;
  <a href="../pages/setting.php">
    <button>Settings</button>
  </a>
  &nbsp;&nbsp;
  <a href="../pages/tools.php">
    <button>Tools</button>
  </a>
  <br>
  <br>
  <a href="logout.php">
    <button>Logout</button>
  </a>
  <br>

</body>

</html>