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
  <title>Welcome to BrickBuddies</title>

  <!-- JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
  </script>
  <script src="assets/js/bootbox.min.js"></script>
  <script src="assets/js/brickbuddies.js"></script>
  <script src="assets/js/jquery.Jcrop.js"></script>
  <script src="assets/js/jcrop_bits.js"></script>


  <!-- CSS  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0&icon_names=delete" />
  <link rel="stylesheet" type="text/css" href="./assets/CSS/styles.css">
  <link rel="stylesheet" href="assets/CSS/jquery.Jcrop.css" type="text/css" />




</head>



<body>

  <div class="top-bar">
    <div class="logo">
      <a href="index.php">BrickBuddies</a>
    </div>

    <nav>
      <a href="" <?php echo  $userLoggedIn?>""><?php echo $user['first_name'] ?></i></a>
      <a href="index.php"><i class="material-icons">home</i></a>
      <a href="#"><i class="material-icons">mail</i></a>
      <a href="#"><i class="material-icons">notifications</i></a>
      <a href="requests.php"><i class="material-icons">group</i></a>
      <a href="#"><i class="material-icons">settings</i></a>
      <a href="includes/handlers/logout.php"><i class="material-icons">logout</i></a>
    </nav>



  </div>
  <div class="wrapper">