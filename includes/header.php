<?php 
require 'config/config.php';

if(isset($_SESSION['username'])) {
  $userLoggedIn = $_SESSION['username'];
  $user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$userLoggedIn'");
  // returns all the columns in the table as an array
  $user = mysqli_fetch_array($user_details_query);
} else {
  header('Location: register.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Willkommen auf Cuddlemuddle</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <link rel="stylesheet" type="text/css" href="./assets/CSS/header_styles.css">
</head>

<body>

  <div class="top-bar">
    <div class="logo">
      <a href="index.php">Cuddlemuddle</a>
    </div>

    <nav>
      <a href="#"><?php echo $user['first_name'] ?></i></a>
      <a href="#"><i class="material-icons">home</i></a>
      <a href="#"><i class="material-icons">mail</i></a>
      <a href="#"><i class="material-icons">notifications</i></a>
      <a href="#"><i class="material-icons">group</i></a>
      <a href="#"><i class="material-icons">settings</i></a>
      <a href="#"><i class="material-icons">logout</i></a>
    </nav>


  </div>