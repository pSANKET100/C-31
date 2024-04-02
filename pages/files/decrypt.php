<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../../index.html");
    exit;
}


include_once "../../connection.php";

function decryptFile($conn, $file_id, $shift, $tableName)
{
    $user_id = $_SESSION['user_id'];

    $query = "SELECT file_name, file_path FROM $tableName WHERE fileid = $1 AND userid = $2";
    $result = pg_query_params($conn, $query, array($file_id, $user_id));
    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $encrypted_file_name = $row['file_name'];
        $encrypted_file_path = $row['file_path'];

        $target_directory = "../../fileuploadtest/uploads/";

        $decrypted_file_name = pathinfo($encrypted_file_name, PATHINFO_FILENAME) . '_dec.' . pathinfo($encrypted_file_name, PATHINFO_EXTENSION);

        $encrypted_content = file_get_contents($encrypted_file_path);
        $decrypted_content = decryptString($encrypted_content, $shift);

        $decrypted_file_path = $target_directory . $decrypted_file_name;
        file_put_contents($decrypted_file_path, $decrypted_content);

        $insert_query = "INSERT INTO decrypted_files (userid, fileid, file_name, file_path, password, upload_date) VALUES ($1, $2, $3, $4, $5, NOW())";
        $insert_result = pg_query_params($conn, $insert_query, array($user_id, $file_id, $decrypted_file_name, $decrypted_file_path, $shift));

        if ($insert_result) {
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

if (isset($_POST['decrypt_file']) && isset($_POST['shift'])) {
    $file_id = $_POST['file_id'];
    $shift = $_POST['shift'];
    $tableName = isset($_POST['table_name']) ? $_POST['table_name'] : 'encrypted_files'; // Default to encrypted_files
    decryptFile($conn, $file_id, $shift, $tableName);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../style/encrypt.css">
    <title>Decrypt File</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
        <a class="navbar-brand" style="margin-left: 4px" href="#">
            <img src="../../assets/padlock.png" width="30" height="30" class="d-inline-block align-top" alt="Files">
            Encryption
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
                    <a class="nav-link" href="../../pages/files/myfiles.php"><img src="../../assets/undo.png" width="20"
                            height="20" class="d-inline-block align-top" alt="Upload"> Back</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../../assets/user.png" alt="User" width="20" height="20">
                        <?php echo $_SESSION['user_name']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#"> <img src="../../assets/user.png" style="height: 20px;"
                                    alt="Settings"> Profile</a></li>
                        <li><a class="dropdown-item" href="#"> <img src="../../assets/settings.png"
                                    style="height: 20px;" alt="Settings"> Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <input type="submit" name="logout" value="Logout">
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <br><br>
    <div class="container">
        <h2>Decrypt File</h2>
        <form method="post">
            <input type="hidden" name="file_id"
                value="<?php echo isset($_GET['file_id']) ? $_GET['file_id'] : ''; ?>" />
            <input type="hidden" name="table_name"
                value="<?php echo isset($_GET['table_name']) ? $_GET['table_name'] : 'encrypted_files'; ?>" />
            <div class="form-group">
                <label for="shift">Shift (1-9):</label>
                <input type="number" class="form-control" id="shift" name="shift" min="1" max="9" required>
            </div><br><br>
            <button type="submit" name="decrypt_file" class="btn btn-outline-danger btn-sm">Decrypt File</button>
        </form>
        <br>
        <a href="myfiles.php">
            <button type="button" class="btn btn-outline-warning btn-sm">Back</button>
        </a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>