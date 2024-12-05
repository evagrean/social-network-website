<?php 

class Post {
private $user_obj;
private $connection;

public function __construct($connection, $user){
  $this->connection = $connection;

  $this->user_obj = new User($connection, $user);
}

public function submitPost($body, $user_to) {
  $body = strip_tags($body);
  $body = mysqli_real_escape_string($this->connection, $body);
  // checking for line brakes and make sure that they are posted/saved correctly
  $body = str_replace('\r\n', '\n', $body);
  $body = nl2br ($body); // replaces new lines with HTML line breaks

  $check_empty = preg_replace('/\s+/', '', $body); //deletes all spaces

  if($check_empty != "") {

    // Current date and time
    $date_added = date("Y-m-d H:i:s");
    // get username
    $added_by = $this->user_obj->getUsername();

    //if user is  on own profike, user_to is 'none'
    if($user_to == $added_by ) {
      $user_to = 'none';
    }

    //insert post
    $query = mysqli_query($this->connection, "INSERT INTO posts VALUES(null, '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0' )");
    $returned_id = mysqli_insert_id($this->connection);

 // Insert notification
 
 // Update post count for user
 $num_posts = $this->user_obj->getNumPosts();
 $num_posts++;
 $update_query = mysqli_query($this->connection, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

  }
}

}



?>