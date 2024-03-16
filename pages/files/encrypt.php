<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Start the session

// Check if the user is not logged in, redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Include database connection
include_once "../../connection.php";

// Function to encrypt the file
function encryptFile($conn, $file_id, $shift)
{
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Fetch file details
    $query = "SELECT file_name, file_path FROM files WHERE fileid = $1 AND userid = $2";
    $result = pg_query_params($conn, $query, array($file_id, $user_id));
    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $file_name = $row['file_name'];
        $file_path = '../../fileuploadtest/' . $row['file_path'];

        // Define the target directory for storing encrypted files
        $target_directory = "../../fileuploadtest/uploads/";

        // Generate a unique file name
        $encrypted_file_name = pathinfo($file_name, PATHINFO_FILENAME) . '_enc.' . pathinfo($file_name, PATHINFO_EXTENSION);

        // Read the file content from the file system
        $file_content = file_get_contents($file_path);

        // Encrypt the file content using the shift
        $encrypted_content = encryptString($file_content, $shift);

        // Write the encrypted content to the target directory
        file_put_contents($target_directory . $encrypted_file_name, $encrypted_content);

        // Store encrypted file details in the database
        $insert_query = "INSERT INTO encrypted_files (userid, file_name, file_path, password) VALUES ($1, $2, $3, $4)";
        $insert_result = pg_query_params($conn, $insert_query, array($user_id, $encrypted_file_name, $target_directory . $encrypted_file_name, $shift));

        if ($insert_result) {
            echo "File encrypted successfully.";
        } else {
            echo "Error storing encrypted file details in the database.";
        }
    } else {
        echo "File not found or you don't have permission to access it.";
    }
}

function encryptString($plaintext, $shift)
{
    $ciphertext = '';
    for ($i = 0; $i < strlen($plaintext); $i++) {
        $char = $plaintext[$i];
        if (ctype_alpha($char)) {
            $offset = ord('a');
            $ascii = ord(strtolower($char));
            $shifted = ($ascii - $offset + $shift) % 26 + $offset;
            $ciphertext .= chr($shifted);
        } else {
            $ciphertext .= $char;
        }
    }
    return $ciphertext;
}

// Check if the form is submitted
if (isset($_POST['encrypt_file']) && isset($_POST['shift'])) {
    $file_id = $_POST['file_id'];
    $shift = $_POST['shift']; // Retrieve the shift value from the form
    encryptFile($conn, $file_id, $shift);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encrypt File</title>
</head>

<body>
    <h2>Encrypt File</h2>
    <form method="post">
        <input type="hidden" name="file_id" value="<?php echo isset($_POST['file_id']) ? $_POST['file_id'] : ''; ?>" />
        Shift (1-9): <input type="number" name="shift" min="1" max="9" required /><br><br>
        <button type="submit" name="encrypt_file">Encrypt File</button>
    </form>
    <br>
    <a href="myfiles.php">
        <button>Back</button>
    </a>
</body>

</html>