<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if the user is not logged in, redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Include database connection
include_once "../../connection.php";

// Modified decryptFile function to accept table name as a parameter
function decryptFile($conn, $file_id, $shift, $tableName)
{
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Validate tableName to ensure it's not malicious
    $allowedTables = ['encrypted_files', 'externally_encrypted_files']; // Define allowed tables
    if (!in_array($tableName, $allowedTables)) {
        echo "Invalid table name.";
        exit; // Stop execution if table name is not allowed
    }

    // Fetch encrypted file details from the specified table
    $query = "SELECT file_name, file_path FROM $tableName WHERE fileid = $1 AND userid = $2";
    $result = pg_query_params($conn, $query, array($file_id, $user_id));
    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $encrypted_file_name = $row['file_name'];
        $encrypted_file_path = $row['file_path'];

        // Define the target directory for storing decrypted files
        $target_directory = "../../fileuploadtest/uploads/";

        // Generate a unique file name
        $decrypted_file_name = pathinfo($encrypted_file_name, PATHINFO_FILENAME) . '_dec.' . pathinfo($encrypted_file_name, PATHINFO_EXTENSION);

        // Read the encrypted file content from the file system
        $encrypted_content = file_get_contents($encrypted_file_path);

        // Decrypt the file content using the shift
        $decrypted_content = decryptString($encrypted_content, $shift);

        // Write the decrypted content to the target directory
        $decrypted_file_path = $target_directory . $decrypted_file_name;
        file_put_contents($decrypted_file_path, $decrypted_content);

        // Store decrypted file details in the database
        $insert_query = "INSERT INTO decrypted_files (userid, file_name, file_path, password, upload_date) VALUES ($1, $2, $3, $4, NOW())";
        $insert_result = pg_query_params($conn, $insert_query, array($user_id, $decrypted_file_name, $decrypted_file_path, $shift));

        if ($insert_result) {
            // Redirect to download.php with the file_id and table parameters
            header("Location: download.php?file_id=$file_id&table=decrypted_files");
            exit;
        } else {
            echo "Error storing decrypted file details in the database.";
        }
    } else {
        echo "File not found or you don't have permission to access it.";
    }
}

function decryptString($ciphertext, $shift)
{
    $plaintext = '';
    for ($i = 0; $i < strlen($ciphertext); $i++) {
        $char = $ciphertext[$i];
        if (ctype_alpha($char)) {
            $offset = ord('a');
            $ascii = ord(strtolower($char));
            $shifted = ($ascii - $offset - $shift + 26) % 26 + $offset;
            $plaintext .= chr($shifted);
        } else {
            $plaintext .= $char;
        }
    }
    return $plaintext;
}

// Check if the form is submitted
if (isset($_POST['decrypt_file']) && isset($_POST['shift']) && isset($_POST['table_name'])) {
    $file_id = $_POST['file_id'];
    $shift = $_POST['shift']; // Retrieve the shift value from the form
    $tableName = $_POST['table_name']; // Directly use the provided table name without defaulting
    decryptFile($conn, $file_id, $shift, $tableName);
} else {
    echo "Required information is missing.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decrypt File</title>
</head>

<body>
    <h2>Decrypt File</h2>
    <form method="post">
        <input type="hidden" name="file_id" value="<?php echo isset($_GET['file_id']) ? $_GET['file_id'] : ''; ?>" />
        <input type="hidden" name="table_name"
            value="<?php echo isset($_GET['table_name']) ? $_GET['table_name'] : ''; ?>" />
        Shift (1-9): <input type="number" name="shift" min="1" max="9" required /><br><br>
        <button type="submit" name="decrypt_file">Decrypt File</button>
    </form>
    <br>
    <a href="myfiles.php">
        <button>Back</button>
    </a>
</body>

</html>