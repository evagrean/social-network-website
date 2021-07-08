<?php

session_start();
$connection = mysqli_connect("localhost", "root", "", "social");
if (mysqli_connect_errno()) {
    echo "Failed to connect " . mysqli_connect_errno();
}

$fname = "";
$lname = "";
$email = "";
$email2 = "";
$password = "";
$password2 = "";
$date = "";
$error_array = array();

if (isset($_POST['reg_button'])) {
    // strip_tags
  $fname = strip_tags($_POST['reg_fname']); //removes possible html tags from input
  $fname = str_replace(' ', '', $fname);
    $fname = ucfirst(strtolower($fname));
    $_SESSION['reg_fname'] = $fname; // stores first name into session

    $lname = strip_tags($_POST['reg_lname']);
    $lname = str_replace(' ', '', $lname);
    $lname = ucfirst(strtolower($lname));
    $_SESSION['reg_lname'] = $lname;

    $email = strip_tags($_POST['reg_email']);
    $email = str_replace(' ', '', $email);
    $email = strtolower($email);
    $_SESSION['reg_email'] = $email;

    $email2 = strip_tags($_POST['reg_email2']);
    $email2 = str_replace(' ', '', $email2);
    $email2 = strtolower($email2);
    $_SESSION['reg_email2'] = $email2;

    $password = strip_tags($_POST['reg_password']);

    $password2 = strip_tags($_POST['reg_password2']);
  
    $date = date("Y-m-d");
  
    if ($email == $email2) {
        // Check if email is in valid format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            // Check if email already exists
            $email_check = mysqli_query($connection, "SELECT email FROM users WHERE email='$email'");

            // Count the number of rows returned
            $num_rows = mysqli_num_rows($email_check);

            if ($num_rows > 0) {
                array_push($error_array, "Email aready in use<br>");
            }
        } else {
            array_push($error_array, "Invalid email format<br>");
        }
    } else {
        array_push($error_array, "Email adresses don't match<br>");
    }

    if (strlen($fname) > 25 || strlen($fname) < 3) {
        array_push($error_array, "Your first name must be between 3 and 25 characters<br>");
    }

    if (strlen($lname) > 25 || strlen($lname) < 3) {
        array_push($error_array, "Your last name must be between 3 and 25 characters<br>");
    }

    if ($password != $password2) {
        array_push($error_array, "Your passwords don't match<br>");
    } else {
        if (preg_match('/[^A-Za-z0-9]', $password)) {
            array_push($error_array, "Your password can only english characters and numbers<br>");
        }
    }

    if (strlen($password) > 30 || strlen($password) < 5) {
        array_push($error_array, "Your password must be between 5 and 30 characters long<br>");
    }

    // if there are no errors
    if (empty($error_array)) {
        $password = md5($password); // Encrypt password before sending to db

        // Generate unsername by concat first name and last name
      $username = strtolower($fname . "_" . $lname); // dot neans "add to a string"
      $check_username_query = mysqli_query($connection, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        // if username exists add number to username
        while (mysqli_num_rows($check_username_query) != 0) {
            $i++;
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($connection, "SELECT username FROM users WHERE username='$username'");
        }

        // Assign a profile picture
        $random = rand(1,5); // Random number between 1 and 5
        if($random == 1) 
        $profile_pic = "assets/images/profile_pics/defaults/head_carrot.png";
        else if($random == 2) 
        $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
        else if($random == 3)
        $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
        else if($random == 4)
        $profile_pic = "assets/images/profile_pics/defaults/head_green_sea.png";
        else if($random == 5)
        $profile_pic = "assets/images/profile_pics/defaults/head_pomegranate.png";


        $query = mysqli_query($connection, "INSERT INTO users VALUES (NULL, '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'no', ',' )");
  $success_message = "<span style='color: #14C800'>You're all set! Go ahead and login!</span><br>";
        array_push($error_array, $success_message);

        // Clear session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
      }
}

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