<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once('config.php');

if (!empty($_POST['username']) && !empty($_POST['password'])) {
  $query = "SELECT id, username FROM user AS u WHERE username = :username AND password = MD5(CONCAT(u.salt, :basePassword))";
  $data = [
    'username' => $_POST['username'],
    'basePassword' => $_POST['password']
  ];
  $statement = $connect->prepare($query);
  $statement->execute($data);

  $result = $statement->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    $_SESSION['user_id'] = $result['id'];
    $_SESSION['username'] = $result['username'];
    header('Location: index.php');
    exit;
  } else {
    $errorMessage = "Invalid username and/or password";
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>To-Do List</title>
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>

    <div class="wrapper">

      <div class='inner-cont'>

        <h1 class="title">TODOlistAPP</h1>

        <form class="form" action="login.php" method="POST">
          <label for="username">Username:</label>
          <input id="username" class="input" type="text" name="username">

          <label for="password">Password:</label>
          <input id="password" class="input" type="password" name="password">


          <input class="button" type="submit" name="login" value="Log In">
        </form>

        <?php if (isset($errorMessage)) {echo($errorMessage);} ?>
        <a class="link-fix" href="register.php">Don't have an account? (Register)</a>

      </div>

    </div>

  </body>
</html>
