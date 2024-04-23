<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../../index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Navbar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        .navbar-nav {
            margin-left: auto;
        }

        .upload-link {
            margin-right: auto;
        }

        .shadow {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5) !important;
        }

        .dropdown-menu li a {
            padding: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
        <a class="navbar-brand" href="#">
            <img src="../../assets/pdf.png" width="30" height="30" class="d-inline-block align-top" alt="Files">
            Files
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <form action="myfiles.php" method="post">
                        <button type="submit" class="btn btn-outline-primary" name="files">
                            All Files
                        </button>

                </li>
                <li class="nav-item">
                    <form action="myfiles.php" method="post">
                        <button type="submit" class="btn btn-outline-primary" name="encrypted">
                            Encrypted Files
                        </button>
                </li>
                <li class="nav-item">
                    <form action="myfiles.php" method="post">
                        <button type="submit" class="btn btn-outline-primary" name="decrypted">
                            Decrypted Files
                        </button>
                </li>
                <li class="nav-item">
                    <form action="myfiles.php" method="post">
                        <button type="submit" class="btn btn-outline-primary" name="externally_encrypted_files">
                            Externally Encrypted Files
                        </button>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../../fileuploadtest/upload.php"><img src="../../assets/upload.png"
                            width="20" height="20" class="d-inline-block align-top" alt="Upload"> Upload</a>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>