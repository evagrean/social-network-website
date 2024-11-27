<?php
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cuddlemuddle</title>
  <link rel="stylesheet" href="assets/CSS/register_styles.css">
</head>
<body>
  <div class="wrapper">
   
    <div class="login-box">

    <div class="login-header">
      <h1>CUDDLEMUDDLE</h1>
      <p>Hier kannst du dich einloggen oder registrieren</p>
    </div>



<form action="register.php" method="POST">
<input type="email" name="log_email" placeholder="Email Address" value="<?php
if(isset($_SESSION['log_email'])) echo $_SESSION['log_email'];
?>" required>
<br>
<input type="password" name="log_password" placeholder="Password" value="<?php
if(isset($_SESSION['log_password'])) echo $_SESSION['log_password'];
?>">
<br>
<input type="submit"name="log_button" value="Login" >
<br>
<?php if(in_array("Email or password was incorrect", $error_array)) echo "Email or password was incorrect";?>
<br>
</form>
  <form action="register.php" method="POST">
  <input type="text" name="reg_fname" placeholder="First Name" value="<?php 
  if(isset($_SESSION['reg_fname'])) {
    echo $_SESSION['reg_fname'];
  }
  ?>" required>
  <br>
  <?php if(in_array($fnameMsg, $error_array)) echo $fnameMsg;?>


  <input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
  if(isset($_SESSION['reg_lname'])) {
    echo $_SESSION['reg_lname'];
  }
  ?>"required>
  <br>
  <?php if(in_array($lnameMsg, $error_array)) echo $lnameMsg;?>

  
  <input type="email" name="reg_email" placeholder="Email" value="<?php 
  if(isset($_SESSION['reg_email'])) {
    echo $_SESSION['reg_email'];
  }
  ?>" required>
  <br>
  <?php 
  if(in_array($doubleEmailMsg, $error_array)) echo $doubleEmailMsg;
  if(in_array($invalidEmailMsg, $error_array)) echo $invalidEmailMsg;
  ?>
  

  <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
  if(isset($_SESSION['reg_email2'])) {
    echo $_SESSION['reg_email2'];
  }
  ?>" required>
  <br>  
  <?php if(in_array($noMatchEmailMsg, $error_array)) echo $noMatchEmailMsg;?>
 

  <input type="password" name="reg_password" placeholder="Password" value="<?php 
  if(isset($_SESSION['reg_password'])) {
    echo $_SESSION['reg_password'];
  }
  ?>" required>
  <br>
  <?php 
  if(in_array($passwordLengthMsg, $error_array)) echo $passwordLengthMsg;
  if(in_array($passwordCharMsg, $error_array)) echo $passwordCharMsg;
  ?>
  
  <input type="password" name="reg_password2" placeholder="Confirm Password" value="<?php 
  if(isset($_SESSION['reg_password2'])) {
    echo $_SESSION['reg_password2'];
  }
  ?>" required>
  <br>
  <?php if(in_array($noMatchPasswordMsg, $error_array)) echo $noMatchPasswordMsg;?>
 



  <input type="submit"name="reg_button" value="Register" >
<br>
<?php 
if(in_array($registerSuccessMsg, $error_array)) echo $registerSuccessMsg;
?>
 
  </form>
  </div>
  </div>
</body>
</html>