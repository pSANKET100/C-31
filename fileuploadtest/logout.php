<?php
session_start(); // Start the session

if (isset($_POST['logout'])) {
    // Destroy the session
    session_unset();
    session_destroy();

    // Redirect to the home page
    header("Location: ../login.php");
    exit;
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
    <h1>Welcome,
        <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Login to view your files.'; ?>
    </h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="submit" name="logout" value="Logout">
    </form>
</body>

</html>