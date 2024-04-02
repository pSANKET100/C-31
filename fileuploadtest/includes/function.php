<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
    <style>
        .btn-sm,
        .new {
            padding: 0.10rem 0.2rem;
            font-size: 0.40rem;
        }

        .new,
        .btn-sm {
            margin: 0 5px;
            background: #fff;
            font-size: 18px;
            padding: 10px 15px;
            border-radius: 6px;
            border: 3px solid #000;
            box-shadow: 5px 5px 0px 0px #000;
            cursor: pointer;
        }
    </style>
</head>

<body>

</body>

</html>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';
function uploadFile()
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $target_dir = "uploads/";

    $directory = 'uploads/';

    if (is_writable($directory)) {
        echo "The directory is writable.";
    } else {
        echo "The directory is not writable.";
    }

    // Check if form submission occurred (prevents unnecessary file checks)
    if (isset($_POST["submit"])) {

        // Get the file name and ensure it is not empty
        $fileName = $_FILES["fileToUpload"]["name"];
        if (empty($fileName)) {
            echo "<script>alert('Error: Please select a file to upload.');</script>";
            exit; // Terminate script execution
        }

        // Extract the file extension (lowercase for case-insensitivity)
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Allow only specific text file extensions (modify as needed)
        $allowedExtensions = array("txt", "csv", "md", "html", "php", "js", "css", "pdf");
        if (!in_array($fileType, $allowedExtensions)) {
            echo "<script>alert('Error: Only " . implode(", ", $allowedExtensions) . " files are allowed.');</script>";
            exit; // Terminate script execution
        }

        // Get the user ID from the session
        session_start();
        $userid = $_SESSION['user_id'];

        // Check if a file with the same name and user ID exists in the database
        global $conn; // Access the database connection globally
        $fileName = $_FILES["fileToUpload"]["name"];

        // Determine the correct table based on the file name
        $tableName = strpos($fileName, '_en') !== false ? 'externally_encrypted_files' : 'files';

        // Check for existing file in the determined table
        $query = "SELECT * FROM $tableName WHERE file_name = $1 AND userid = $2";
        $params = array($fileName, $userid);
        $result = pg_query_params($conn, $query, $params);

        if (pg_num_rows($result) > 0) {
            echo "<script>alert('Error: A file with the same name already exists.');</script>";
            exit; // Terminate script execution
        }

        // Attempt to move the uploaded file to the target location
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $fileName)) {
            echo "<script>alert('The file " . htmlspecialchars($fileName) . " has been uploaded successfully.');</script>";

            // Get the file path
            $filePath = $target_dir . $fileName;

            // Get the current date and time
            $uploadDate = date('Y-m-d H:i:s');

            // Store the file details in the determined table
            $query = "INSERT INTO $tableName (userid, file_name, file_path, upload_date) VALUES ($1, $2, $3, $4)";
            $params = array($userid, $fileName, $filePath, $uploadDate);
            pg_query_params($conn, $query, $params);
        } else {
            echo "<script>alert('Error: An error occurred while uploading the file.');</script>";
        }
    }
}


function displayFiles($conn, $table)
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Query to fetch files from the specified table for the current user
    $query = "SELECT fileid, file_name, file_path, upload_date FROM $table WHERE userid = $1";
    $result = pg_query_params($conn, $query, array($user_id));

    if ($result && pg_num_rows($result) > 0) {
        // Display files in a table with Bootstrap styling inside a container
        echo "<div class='container'>";
        echo "<div class='row justify-content-center'>"; // Center the row
        echo "<div class='col-md-10'>"; // Adjust column width as needed
        echo "<div class='table-responsive'>";
        echo "<table class='table table-sm table-striped table-bordered table-hover' style='width: 100%;'>"; // Adjust width here
        echo "<thead class='thead-dark'><tr><th>File Name</th><th>Upload Date</th><th>Action</th></tr></thead>";
        echo "<tbody>";

        while ($row = pg_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['file_name']}</td>";
            echo "<td>{$row['upload_date']}</td>";
            echo "<td>";

            // Display different action buttons based on the table
            if ($table === 'files') {
                // For files table, display Encrypt button
                echo "<form method='post' action='encrypt.php' style='display:inline-block; margin-right: 5px;'>
                            <input type='hidden' name='file_id' value='{$row['fileid']}' />
                             <button type='submit' name='encrypt_file' class='btn btn-sm btn-primary'>Encrypt</button>
                        </form>";
            } elseif ($table === 'decrypted_files') {
                // For decrypted_files table, display Decrypt and Download buttons
                echo "<form method='post' action='delete.php' style='display:inline-block; margin-right: 5px;'>
                        <input type='hidden' name='file_id' value='{$row['fileid']}' />
                        <input type='hidden' name='table_name' value='{$table}' />
                        <button type='submit' name='delete_file' class='btn btn-sm btn-danger'>Delete</button>
                      </form>";

                // Display download button
                echo "<a href='download.php?file_id={$row['fileid']}&table=$table' class='btn btn-sm btn-success'>Download</a>";
            } elseif ($table === 'encrypted_files') {
                // For encrypted_files table, display Decrypt button
                echo "<form method='get' action='decrypt.php' style='display:inline-block; margin-right: 5px;'>
                        <input type='hidden' name='file_id' value='{$row['fileid']}' />
                        <input type='hidden' name='table_name' value='{$table}' />
                        <button type='submit' name='decrypt_file' class='btn btn-sm btn-warning'>Decrypt</button>
                      </form>";
            } elseif ($table === 'externally_encrypted_files') {
                echo "<form method='get' action='decrypt.php' style='display:inline-block; margin-right: 5px;'>
                        <input type='hidden' name='file_id' value='{$row['fileid']}' />
                        <input type='hidden' name='table_name' value='{$table}' />
                        <button type='submit' name='decrypt_file' class='btn btn-sm btn-info'>Decrypt Externally</button>
                      </form>";
            }

            // Check if the file has been decrypted
            $file_path = $row['file_path'];
            $file_exists = file_exists($file_path);

            // Display download button if file has been decrypted, otherwise, disable it
            if ($file_exists) {
                echo "<a href='download.php?file_id={$row['fileid']}&table=$table' class='btn btn-sm btn-success'>Download</a>";
            } else {
                echo "<button disabled class='btn btn-sm btn-success'>Download</button>";
            }

            // Display delete button for all tables except decrypted_files
            if ($table !== 'decrypted_files') {
                echo "<form method='post' action='delete.php' style='display:inline-block; margin-right: 5px;'>
                        <input type='hidden' name='file_id' value='{$row['fileid']}' />
                        <input type='hidden' name='table_name' value='{$table}' />
                        <button type='submit' name='delete_file' class='btn btn-sm btn-danger'>Delete</button>
                      </form>";
            }

            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>"; // Close the column
        echo "</div>"; // Close the row
        echo "</div>"; // Close the container div
    } else {
        echo "<p class='text-center'>No files found.</p>";
    }
}
