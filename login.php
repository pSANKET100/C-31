<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <style>
    body {
      margin: 0px;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .center-container {
      text-align: center;
    }
  </style>
</head>

<body>
  <div>
    <h1 class="center-container">Login</h1>
    <div>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label><br />
        <input type="text" id="email" name="email" placeholder="Enter email" /><br /><br />
        <label for="password">Password:</label><br />
        <input type="password" id="password" name="password" placeholder="Enter password" /><br /><br />
        <label for="rememberMe">
        <input type="submit" name="login" value="Login">
        <br><br>
        Don't have an account? <a href="signup.html">Register</a>
      </form>
    </div>
  </div>

  <?php
  include "connection.php";

  if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate login credentials
    $query = "SELECT * FROM users WHERE email = $1 AND password = $2";
    $result = pg_query_params($conn, $query, array($email, $password));

    if ($result && pg_num_rows($result) > 0) {
      // Successful login
      ?>
      <script>
        alert("Login successful");
        // Redirect to the desired page
        window.location.href = "html/dashboard.html";
      </script>
      <?php
      exit();
    } else {
      // Failed login
      ?>
      <script>
        alert("Invalid email or password. Please try again.");
      </script>
      <?php
    }
  }
  ?>
</body>

</html>