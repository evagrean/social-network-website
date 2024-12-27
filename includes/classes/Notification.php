<?php 

class Notification {
private $user_obj;
private $connection;

public function __construct($connection, $user){
  $this->connection = $connection;

  $this->user_obj = new User($connection, $user);
}

public function getUnreadNumber() {
  $userLoggedIn = $this->user_obj->getUsername();
  $query = mysqli_query($this->connection, "SELECT * FROM notifications WHERE viewed='no' AND user_to='$userLoggedIn'");
  return mysqli_num_rows($query);
}
}

?>