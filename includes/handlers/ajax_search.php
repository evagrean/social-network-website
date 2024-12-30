<?php 

include("../../config/config.php");
include("../../includes/classes/User.php");

// Get the values we are passing to ajax_search.php from brickbuddies.js
$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

// explode makes it possible to search first and last name
$names = explode(" ", $query);


// If query contains an unsercore, assume user is searching for usernames
if (strpos($query, '_') !== false) {
  $usersReturnedQuery = mysqli_query($connection, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");

// If there are two words, assume theiy are first and last names respectively
} else if (count($names) == 2) {
  $usersReturnedQuery = mysqli_query($connection, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no' LIMIT 8");
// If query has one word search first names and last names
} else {
  $usersReturnedQuery = mysqli_query($connection, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no' LIMIT 8");
}

if ($query != "") {

  while($row = mysqli_fetch_array($usersReturnedQuery)) {
    $user = new User($connection, $userLoggedIn);
      if ($row['username'] != $userLoggedIn) {
        $mutual_friends = $user->getMutualFriends($row['username']) . " friends in common";
      
      } else {
        $mutual_friends = "";
      }

      echo "<div class='resultDisplay'> 
              <a href='" . $row['username'] . "' style='color: var(--primary-color)'>
                <div class='liveSearchProfilePic'>
                  <img src='" . $row['profile_pic'] . "'>
                </div>
                <div class='liveSearchText'>
                " . $row['first_name'] . " " . $row['last_name'] .  "
                <p>" . $row['username'] ."</p>
                <p id='grey'>" . $mutual_friends ."</p>
                </div>
              </a>
            </div>";

  

  }
}




?>