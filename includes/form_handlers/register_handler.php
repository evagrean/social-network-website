<?php
// Declaring variables to prevent errors
$fname="";
$fname="";
$email="";
$email2="";
$password="";
$password2="";
$date=""; // Sign up date
$error_array= array(); // Holds error messages

$fnameMsg = "Your first name must be between 2 and 25 characters long<br>";
$lnameMsg = "Your last name must be between 2 and 25 characters long<br>";
$doubleEmailMsg = "Email already in use<br>";
$invalidEmailMsg = "Invalid Email format<br>";
$noMatchEmailMsg = "Email does not match<br>";
$noMatchPasswordMsg = "Your passwords do not match<br>";
$passwordCharMsg = "Your password can only contain characters or numbers<br>";
$passwordLengthMsg = "Your password must be between 5 and 30 characters<br>";
$registerSuccessMsg = "<span style='color: #14CB00;'>You're all set! Go ahead and login!</span><br>";
$loginFailMsg = "Email or password was incorrect<br>";



if(isset($_POST['reg_button'])) {
  // Registration form values
  $fname = strip_tags($_POST['reg_fname']); // strip_tags removes HTML tags from text
  $fname = str_replace(' ', '', $fname);
  $fname = ucfirst(strtolower($fname)); // captializes first letter
  $_SESSION['reg_fname'] = $fname;

  $lname = strip_tags($_POST['reg_lname']); 
  $lname = str_replace(' ', '', $lname);
  $lname = ucfirst(strtolower($lname));
  $_SESSION['reg_lname'] = $lname;

  $email = strip_tags($_POST['reg_email']); 
  $email = str_replace(' ', '', $email);
  $_SESSION['reg_email'] = $email;


  $email2 = strip_tags($_POST['reg_email2']); 
  $email2 = str_replace(' ', '', $email2); 
  $_SESSION['reg_email2'] = $email2;

  $password = strip_tags($_POST['reg_password']); 
  $_SESSION['reg_password'] = $password;

  $password2 = strip_tags($_POST['reg_password2']); 
  $_SESSION['reg_password2'] = $password2;

  $date = date("Y-m-d");

  if($email == $email2) {
    // check valid format
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    // Check if email already exists
    $email_check = mysqli_query($connection, "SELECT email FROM users WHERE email='$email'");
    // Count numer of rows returned
  $num_rows = mysqli_num_rows($email_check);

  if($num_rows > 0) {
    array_push($error_array, $doubleEmailMsg);
    
  }

    } else {
      array_push($error_array, $invalidEmailMsg);
 
    }

  } else {
    array_push($error_array, $invalidEmailMsg);
  
  }

  if(strlen($fname) > 25 || strlen($fname) < 2) {
    array_push($error_array, $fnameMsg);
    
  };

  if(strlen($lname) > 25 || strlen($lname) < 2) {
    array_push($error_array, $lnameMsg);
    
  };

  if($password != $password2) {
    array_push($error_array, $noMatchPasswordMsg);

  }  else {
    if(preg_match('/[^A-Za-z=0-9]/', $password)) {
      array_push($error_array, $passwordCharMsg);

    };
  };

  if(strlen($password) > 30 || strlen($password) < 5) {
    array_push($error_array, $passwordLengthMsg);
   
  }

  if(empty($error_array)) {
    $password = md5($password);

    // Generate username
    $username = strtolower($fname . "_" . $lname);
    $checkUsernameQuery = mysqli_query($connection, "SELECT username from users WHERE username='$username'");

    $i = 0;
    while(mysqli_num_rows($checkUsernameQuery) != 0) {
      $i++;
      $username = $username . "_" . $i;
      $checkUsernameQuery = mysqli_query($connection, "SELECT username from users WHERE username='$username'");
  };

    // Assign a profile picture
    $random = rand(1,5); // Random number between 1 and 5
    if($random == 1) 
    $profilePic = "assets/images/profile_pics/defaults/head_carrot.png";
    else if($random == 2) 
    $profilePic = "assets/images/profile_pics/defaults/head_deep_blue.png";
    else if($random == 3)
    $profilePic = "assets/images/profile_pics/defaults/head_emerald.png";
    else if($random == 4)
    $profilePic = "assets/images/profile_pics/defaults/head_green_sea.png";
    else if($random == 5)
    $profilePic = "assets/images/profile_pics/defaults/head_pomegranate.png";

    $query = mysqli_query($connection, "INSERT INTO users VALUES (NULL, '$fname', '$lname', '$username', '$email', '$password', '$date', '$profilePic', '0', '0', 'no', ',' )" );
    array_push($error_array, $registerSuccessMsg);

    // clear session variables after submission

    $_SESSION['reg_fname'] = "";
    $_SESSION['reg_lname'] = "";
    $_SESSION['reg_email'] = "";
    $_SESSION['reg_email2'] = "";
    $_SESSION['reg_password'] = "";
    $_SESSION['reg_password2'] = "";
 
  }
};

?>