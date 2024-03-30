<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'includes/function.php';
include '../fileuploadtest/includes/connection.php';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Upload the file
    uploadFile();

    // Redirect to the specified page after upload
    header("Location: ../pages/files/myfiles.php");
    exit; // Ensure no further code is executed after redirection
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: white;
        display: flex;
        justify-content:center;
        align-items: center;
    }
    .container {
        background-color: white;
        border-radius: 20px;
        padding: 20px;
        width: 200%;
        max-width: 500px;
        /* Adjust as needed */
        text-align: center;
    }

    h2 {
        color: black;
        margin-bottom: 20px;
    }

    form {
        text-align: center;
    }

    button[type="button"] {
        background-color:#ffed00;
        border-radius: 10px;
        padding: 30px;
        width: 80%;
    }
    button{
        font-family: Arial, sans-serif;
        font-size: 30px;
    }
</style>
</head>
<body>
    <div class="container">
        <h2>Upload Files</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <!-- Remove the input tag -->
            <label for="fileToUpload"></label><br>
            <!-- You can optionally add a button to trigger file selection -->
            <input type="file" name="fileToUpload" id="fileToUpload" style="display: none;">
            <button type="button" onclick="document.getElementById('fileToUpload').click();">Select TxT File</button>
        </form>
    </div>
</body>

</html>