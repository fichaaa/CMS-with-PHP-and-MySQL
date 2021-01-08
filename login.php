<?php

require 'includes/init.php';


if(Auth::isLoggedIn()){
  die('You are already logged in.');
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

  $conn = require 'includes/db.php';

  $username = $_POST['username'];
  $password = $_POST['password'];

  if(User::authenticate($conn, $username, $password)){
    Auth::login();

    Url::redirect('/');
  }

}

?>


<?php require 'includes/header.php'; ?>

<?php require 'includes/nav.php'; ?>

<h3>Log in form</h3>
<form method="post" class="form-container">
  <div class="form-group">
    <label for="username">Enter username</label>
    <input type="text" id="username" name="username" class="form-control">
  </div>
  <div class="form-group">
    <label for="password">Enter password</label>
    <input type="password" id="password" name="password" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary btn-block">Log in</button>
</form>
<?php require 'includes/footer.php'; ?>