<?php

class User {
  private $user;
  private $connection;

public function __construct($connection, $user) {
  $this->connection = $connection;
  $user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$user'");
  $this->user = mysqli_fetch_array($user_details_query);
}

public function getUsername() {
  return $this->user['username'];
}

public function getNumberOfFriendRequests() {
  $username = $this->user['username'];
  $query = mysqli_query($this->connection, "SELECT * FROM friend_requests WHERE user_to='$username'");
  return mysqli_num_rows($query);
}

public function getNumPosts() {
  $username = $this->user['username'];
  $query = mysqli_query($this->connection, "SELECT num_posts FROM users WHERE username='$username'");
  $row = mysqli_fetch_array($query);
  return $row['num_posts'];
}

public function getFirstAndLastName() {
  $username = $this->user['username'];
  $query = mysqli_query($this->connection, "SELECT first_name, last_name FROM users WHERE username='$username'");
  $row = mysqli_fetch_array($query);
  return $row['first_name'] . " " . $row['last_name'];
}

public function getProfilePic() {
  $username = $this->user['username'];
  $query = mysqli_query($this->connection, "SELECT profile_pic FROM users WHERE username='$username'");
  $row = mysqli_fetch_array($query);
  return $row['profile_pic'];
}

public function getFriendArray() {
  $username = $this->user['username'];
  $query = mysqli_query($this->connection, "SELECT friend_array FROM users WHERE username='$username'");
  $row = mysqli_fetch_array($query);
  return $row['friend_array'];
}

public function isClosed() {
  $username = $this->user['username'];
  $query = mysqli_query($this->connection, "SELECT user_closed FROM users WHERE username='$username'");
  $row = mysqli_fetch_array($query);

  if($row['user_closed'] == 'yes') {
    return true;
  } else {
    return false;
  }
}

public function isFriend($username_to_check) {
$usernameComma = "," . $username_to_check . ",";

if ((strstr($this->user['friend_array'], $usernameComma)) || $username_to_check == $this->user['username']) {

return true;
} else {
  return false;
}
}

public function didReceiveRequest($user_from) {
  $user_to = $this->user['username'];
  $check_request_query = mysqli_query($this->connection, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'");
  if (mysqli_num_rows($check_request_query) > 0) {
    return true;
  } else {
    return false;
  }

}

public function didSendRequest($user_to) {
  $user_from = $this->user['username'];
  $check_request_query = mysqli_query($this->connection, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'");
  if (mysqli_num_rows($check_request_query) > 0) {
    return true;
  } else {
    return false;
  }
}

public function removeFriend($username_to_remove) {
$loggedInUser = $this->user['username'];

// Get the friend array from username to remove
$friend_array_query = mysqli_query($this->connection, "SELECT friend_array FROM users WHERE username='$username_to_remove'");
$row = mysqli_fetch_array($friend_array_query);
$friend_array_username = $row['friend_array'];

// logged in user friend array already here, update it
$array_updated = str_replace($username_to_remove . ",", "", $this->user['friend_array']);
// Remove it from logged in user
$remove_friend_query = mysqli_query($this->connection, "UPDATE users SET friend_array='$array_updated' WHERE username='$loggedInUser'");

// remove it from former friend also
$array_updated = str_replace($loggedInUser . ",", "", $friend_array_username);
$remove_friend_query = mysqli_query($this->connection, "UPDATE users SET friend_array='$array_updated' WHERE username='$username_to_remove'");


}

public function sendRequest($user_to) {
  $user_from = $this->user['username'];
$query = mysqli_query($this->connection, "INSERT INTO friend_requests VALUES(null, '$user_to', '$user_from')");


}

public function getMutualFriends($user_to_check) {
$mutualFriends = 0;
$user_array = $this->user['friend_array'];
// Friend array for the user logged in
$user_array_explode = explode(",", $user_array); // explode splits string into array at given delimiter

// Friend array for the user to check
$query = mysqli_query($this->connection, "SELECT friend_array from users WHERE username='$user_to_check'");
$row = mysqli_fetch_array($query);
$user_to_check_array = $row['friend_array'];
$user_to_check_array_explode = explode(",", $user_to_check_array);

foreach($user_array_explode as $i) {

  foreach($user_to_check_array_explode as $j) {

    if($i == $j && $i != "") {
      $mutualFriends++;
    }

  }
}
return $mutualFriends;

}

  
}

?>