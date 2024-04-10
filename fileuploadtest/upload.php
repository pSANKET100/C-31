<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'includes/function.php';
include '../connection.php';
session_start();
if (isset($_POST['submit'])) {
    uploadFile();

    header("Location: ../pages/files/myfiles.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        @import url(../style/encrypt.css);

        /* body {
            padding: 20px;
        } */

        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
        <a class="navbar-brand" href="#">
            <img src="../assets/upload.png" width="30" height="30" class="d-inline-block align-top"
                style="margin-left: 4px;" alt="Files">
            Upload
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link " style="padding-right: 10px;" href="../pages/files/myfiles.php"><img
                            src="../assets/undo.png" width="20" height="20" class="d-inline-block align-top"
                            alt="Upload"> Back</a>
                </li>
            </ul>
        </div>
    </nav><br><br>
    <div class="container">
        <br><br>
        <h2>Upload Files</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
            enctype="multipart/form-data">
            <div class="mb-3">
                <label for="fileToUpload" class="form-label">Select file to upload:</label>
                <input type="file" class="form-control" id="fileToUpload" name="fileToUpload">
            </div>
            <button type="submit" class="btn btn-outline-success btn-sm" name="submit">Upload File</button>
        </form>
        <br>
        <a href="../pages/files/myfiles.php">
            <button type="button" class="btn btn-outline-warning btn-sm">Back</button>
        </a>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>