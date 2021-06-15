<?php
require 'includes/p_init.php';
// IF USER MAKING LOGIN REQUEST
if(isset($_POST['email']) && isset($_POST['password'])){
  $result = $pat_obj->loginPatient($_POST['email'],$_POST['password']);
}
// IF USER ALREADY LOGGED IN
if(isset($_SESSION['email'])){
  header('Location: p_profile.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Patient Login</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
</head>
<body style="background-color: black;">
  <div class="main_container login_signup_container" style="margin-top: 120px;">
    <h1>Patient Login</h1>
    <form action="" method="POST">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" spellcheck="false" placeholder="Enter your email address" required>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password" required>
      <input type="submit" value="Login">
      <a href="pat_reg.php" class="form_link">Sign Up</a>
      <div>  
      <?php
        if(isset($result['errorMessage'])){
          echo '<p class="errorMsg">'.$result['errorMessage'].'</p>';
        }
        if(isset($result['successMessage'])){
          echo '<p class="successMsg">'.$result['successMessage'].'</p>';
        }
      ?>    
    </div>
    </form>
    
  </div>
</body>
</html>