<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once('config.php');


if (!empty($_POST['register'])) {

  if (strlen($_POST['username'])<6) {
    echo '<h3>Username too short</h3>';
  } elseif ($_POST['password'] !== $_POST['confirmPassword']) {
    echo '<h3>Passwords must match</h3>';
  } elseif (empty($_POST['password'])) {
    echo '<h3>Please type password</h3>';
  } else {

    $salt = 'phpdev20';

    $password = md5($salt . $_POST['password']);

    $query = "INSERT INTO user
    (username, password, salt)
    VALUES (:username, :password, :salt)";
    $statement= $connect->prepare($query);
    $data = [
        'username' => $_POST['username'],
        'password' => $password,
        'salt' => $salt
    ];
    $statement->execute($data);

    if ($statement->errorInfo()[2]) {
      var_dump($statement->errorInfo());
    } else {
      echo '<h3>Succesfully registered. Please <a href="login.php">Log in</a></h3>';
    }
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

        <form class="form" action="register.php" method="POST">
          <label for="username">Username:</label>
          <input id="username" class="input" type="text" name="username">

          <label for="password">Password:</label>
          <input id="password" class="input" type="password" name="password">

          <label for="confirmPassword">Confirm Password:</label>
          <input id="confirmPassword" class="input" type="password" name="confirmPassword">

          <input class="button" type="submit" name="register" value="Register">

        </form>

        <a class="link-fix" href="login.php">Already have an account? (Login)</a>

      </div>
    </div>
  </body>
</hmtl>
