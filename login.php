<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header("Location: pages/files/myfiles.php");
  exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST["login"])) {
  require_once "connection.php";

  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = "SELECT * FROM users WHERE email = $1 AND password = $2";
  $result = pg_query_params($conn, $query, array($email, $password));

  if ($result && pg_num_rows($result) > 0) {
    $user_row = pg_fetch_assoc($result);
    $_SESSION['user_id'] = $user_row['userid']; // Store user ID in session
    $_SESSION['user_name'] = $user_row['name']; // Store user name in session
    header("Location: pages/files/myfiles.php");
    exit();
  } else {
    echo "<script>alert('Email or password is incorrect.');</script>";
  }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style/login.css">
  <title>Login</title>
</head>

<body>
  <?php if (isset($error_message)): ?>
    <p>
      <?php echo $error_message; ?>
    </p>
  <?php endif; ?>
  <div class="container">
    <div class="card">
      <h1>Login</h1>
      <div class="form-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <input type="email" id="email" name="email" placeholder="Enter email" required /><br /><br />
          <input type="password" id="password" name="password" placeholder="Enter password" required /><br /><br />
          <input type="submit" name="login" value="Login" />
        </form>
      </div>
      <div class="signup-link">
        Don't have an account? <a href="signup.php">Register</a>
      </div>
    </div>
  </div>
</body>

</html>