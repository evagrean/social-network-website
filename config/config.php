<?php
ob_start(); // Turns on output buffering
session_start();

$timezone = date_default_timezone_set('Europe/Berlin');

$connection = mysqli_connect("localhost", "root", "", "social-network");
if (mysqli_connect_errno()) {
    echo "Failed to connect " . mysqli_connect_errno();
}
