<?php 
require 'config/config.php';

if(isset($_SESSION['username'])) {
  $userLoggedIn = $_SESSION['username'];
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
  <link rel="stylesheet" type="text/css" href="assets/CSS/header_styles.css">
</head>

<body>

  <div class="top-bar">
    <div class="logo">
      <a href="index.php">Cuddlemuddle</a>
    </div>

  </div>