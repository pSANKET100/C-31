<?php
session_start(); // Start the session

// Check if the user is already logged in, redirect to dashboard if logged in
if (isset ($_SESSION['user_id'])) {
  header("Location: fileuploadtest/index.php");
  exit;
}

// Enable error reporting for debugging purposes (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form submission occurred
if (isset ($_POST["login"])) {
  // Include database connection
  require_once "connection.php";

  $email = $_POST['email'];
  $password = $_POST['password'];

  // Validate login credentials
  $query = "SELECT * FROM users WHERE email = $1 AND password = $2";
  $result = pg_query_params($conn, $query, array($email, $password));

  if ($result && pg_num_rows($result) > 0) {
    // Successful login
    $user_row = pg_fetch_assoc($result);
    $_SESSION['user_id'] = $user_row['userid']; // Store user ID in session
    $_SESSION['user_name'] = $user_row['name']; // Store user name in session
    header("Location: fileuploadtest/index.php");
    exit();
  } else {
    // Failed login
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
  <nav>
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="/html/about.html">About Us</a></li>
      <li><a href="#">Services</a></li>
      <div class="dropdown"></div>
      <li><a href="#">Contact</a></li>
    </ul>
  </nav>
  <?php if (isset ($error_message)): ?>
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