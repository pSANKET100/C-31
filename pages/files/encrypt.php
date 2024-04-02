<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// var_dump($_POST);
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
function encryptFile($conn, $file_id, $shift)
{
    $user_id = $_SESSION['user_id'];

    $query = "SELECT file_name, file_path FROM files WHERE fileid = $1 AND userid = $2";
    $result = pg_query_params($conn, $query, array($file_id, $user_id));
    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $file_name = $row['file_name'];
        $file_path = '../../fileuploadtest/' . $row['file_path'];

        $target_directory = "../../fileuploadtest/uploads/";

        $encrypted_file_name = pathinfo($file_name, PATHINFO_FILENAME) . '_enc.' . pathinfo($file_name, PATHINFO_EXTENSION);

        $file_content = file_get_contents($file_path);

        $encrypted_content = encryptString($file_content, $shift);

        file_put_contents($target_directory . $encrypted_file_name, $encrypted_content);

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
if (isset($_POST['encrypt_file']) && isset($_POST['shift'])) {
    $file_id = $_POST['file_id'];
    $shift = $_POST['shift'];
    encryptFile($conn, $file_id, $shift);
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
    <title>Encrypt File</title>
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
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
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
        <h2>Encrypt File</h2>
        <form method="post">
            <input type="hidden" name="file_id"
                value="<?php echo isset($_POST['file_id']) ? $_POST['file_id'] : ''; ?>" />
            Shift (1-9): <input type="number" name="shift" min="1" max="9" required /><br><br>
            <button type="submit" class="btn btn-outline-danger btn-sm" name="encrypt_file">Encrypt File</button>

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