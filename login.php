<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="login.css">
  <title>Document</title>

</head>

<body>
  <div class="container">
    <div class="card">
      <h1>Login</h1>
      <div class="form-container">
        <!-- Form for user login -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <!-- <label for="email">Email:</label> -->
          <input type="text" id="email" name="email" placeholder="Enter email" required /><br /><br />
          <!-- <label for="password">Password:</label> -->
          <input type="password" id="password" name="password" placeholder="Enter password" required /><br /><br />
          <input type="submit" name="login" value="Login" />
        </form>
      </div>
      <div class="signup-link">
        Don't have an account? <a href="signup.php">Register</a>
      </div>
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