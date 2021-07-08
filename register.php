<?php
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <form action="register.php" method="POST">
  <input type="text" name="reg_fname" placeholder="First Name" value="<?php
  if (isset($_SESSION['reg_fname'])) {
      echo $_SESSION['reg_fname'];
  }
  ?>" required>
  <br>
  <?php if (in_array("Your first name must be between 3 and 25 characters<br>", $error_array)) {
      echo "Your first name must be between 3 and 25 characters<br>";
  }?>


  <input type="text" name="reg_lname" placeholder="Last Name" value="<?php
  if (isset($_SESSION['reg_lname'])) {
      echo $_SESSION['reg_lname'];
  }
  ?>" required>
  <br>
  <?php if (in_array("Your last name must be between 3 and 25 characters<br>", $error_array)) {
      echo "Your last name must be between 3 and 25 characters<br>";
  }?>
  
  <input type="email" name="reg_email" placeholder="Email" value="<?php
  if (isset($_SESSION['reg_email'])) {
      echo $_SESSION['reg_email'];
  }
  ?>"required>
  <br>

  <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php
  if (isset($_SESSION['reg_email2'])) {
      echo $_SESSION['reg_email2'];
  }
  ?>"required>
  <br>  
  <?php if (in_array("Email aready in use<br>", $error_array)) {
      echo "Email aready in use<br>";
  } elseif (in_array("Invalid email format<br>", $error_array)) {
      echo  "Invalid email format<br>";
  } elseif (in_array("Email adresses don't match<br>", $error_array)) {
      echo  "Email adresses don't match<br>";
  }?>
  

  <input type="password" name="reg_password" placeholder="Password" required>
  <br>
  
  <input type="password" name="reg_password2" placeholder="Confirm Password" required>
  <br>
  <?php if (in_array("Your passwords don't match<br>", $error_array)) {
      echo "Your passwords don't match<br>";
  } elseif (in_array("Your password can only english characters and numbers<br>", $error_array)) {
      echo  "Your password can only english characters and numbers<br>";
  } elseif (in_array("Your password must be between 5 and 30 characters long<br>", $error_array)) {
      echo  "Your password must be between 5 and 30 characters long<br>" ;
  }?>



  <input type="submit"name="reg_button" value="Register" >
<br>
  <?php if (in_array($success_message, $error_array)) 
      echo $success_message;?>
  </form>
</body>
</html>