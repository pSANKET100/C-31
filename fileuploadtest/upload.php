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

<body>
    <h2>Upload Files</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="submit">
    </form>
</body>

</html>