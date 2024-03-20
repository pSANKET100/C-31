<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include the database connection
include 'connection.php';

function uploadFile()
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // include 'connection.php';
    // Set the target directory for uploaded files (modify as needed)
    $target_dir = "uploads/";

    $directory = 'uploads/';

    if (is_writable($directory)) {
        echo "The directory is writable.";
    } else {
        echo "The directory is not writable.";
    }

    // Check if form submission occurred (prevents unnecessary file checks)
    if (isset ($_POST["submit"])) {

        // Get the file name and ensure it is not empty
        $fileName = $_FILES["fileToUpload"]["name"];
        if (empty ($fileName)) {
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

            // if (!$tableName) {
            //     $query = "INSERT INTO files (userid, file_name, file_path, upload_date) VALUES ($1, $2, $3, $4)";
            //     $params = array($userid, $fileName, $filePath, $uploadDate);
            //     pg_query_params($conn, $query, $params);
            // }

            // Redirect or additional logic as needed...

        } else {
            echo "<script>alert('Error: An error occurred while uploading the file.');</script>";
        }
    }
}

// Function to fetch and display files for a given table
function displayFiles($conn, $table)
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Query to fetch files from the specified table for the current user
    $query = "SELECT fileid, file_name, file_path FROM $table WHERE userid = $1";
    $result = pg_query_params($conn, $query, array($user_id));

    if ($result && pg_num_rows($result) > 0) {
        // Display files in a table
        echo "<table border='1'>";
        echo "<tr><th>File Name</th><th>Action</th></tr>";

        while ($row = pg_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td style='text-align:center;'>{$row['file_name']}</td>";
            echo "<td style='text-align:center;'>";

            // Display different action buttons based on the table
            if ($table === 'files') {
                // For files table, display Encrypt button
                echo "<form method='post' action='encrypt.php' style='display:inline-block;'>
                            <input type='hidden' name='file_id' value='{$row['fileid']}' />
                             <button type='submit' name='encrypt_file'>Encrypt</button>
                        </form>";
            } elseif ($table === 'decrypted_files') {
                // For decrypted_files table, display Decrypt and Download buttons
                echo "<form method='post' action='delete.php' style='display:inline-block;'>
                        <input type='hidden' name='file_id' value='{$row['fileid']}' />
                        <input type='hidden' name='table_name' value='{$table}' />
                        <button type='submit' name='delete_file'>Delete</button>
                      </form>";

                // Display download button
                echo "<a href='download.php?file_id={$row['fileid']}&table=$table'><button>Download</button></a>";
            } elseif ($table === 'encrypted_files') {
                // For encrypted_files table, display Decrypt button
                echo "<form method='get' action='decrypt.php' style='display:inline-block;'>
                        <input type='hidden' name='file_id' value='{$row['fileid']}' />
                        <input type='hidden' name='table_name' value='{$table}' />
                        <button type='submit' name='decrypt_file'>Decrypt</button>
                      </form>";
            } elseif ($table === 'externally_encrypted_files') {
                // For externally_encrypted_files table, display Decrypt Externally button
                echo "<form method='get' action='decrypt.php' style='display:inline-block;'>
                        <input type='hidden' name='file_id' value='{$row['fileid']}' />
                        <input type='hidden' name='table_name' value='{$table}' />
                        <button type='submit' name='decrypt_file'>Decrypt Externally</button>
                      </form>";
            }

            // Check if the file has been decrypted
            $file_path = $row['file_path'];
            $file_exists = file_exists($file_path);

            // Display download button if file has been decrypted, otherwise, disable it
            if ($file_exists) {
                echo "<a href='download.php?file_id={$row['fileid']}&table=$table'><button>Download</button></a>";
            } else {
                echo "<button disabled>Download</button>";
            }

            // Display delete button for all tables except decrypted_files
            if ($table !== 'decrypted_files') {
                echo "<form method='post' action='delete.php' style='display:inline-block;'>
                        <input type='hidden' name='file_id' value='{$row['fileid']}' />
                        <input type='hidden' name='table_name' value='{$table}' />
                        <button type='submit' name='delete_file'>Delete</button>
                      </form>";
            }

            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No files found.";
    }
}
