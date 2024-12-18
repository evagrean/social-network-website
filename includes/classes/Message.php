<?php 

class Message {
private $user_obj;
private $connection;

public function __construct($connection, $user){
  $this->connection = $connection;

  $this->user_obj = new User($connection, $user);
}

public function getMostRecentUser() {
  $userLoggedIn = $this->user_obj->getUsername();

  $query = mysqli_query($this->connection, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC LIMIT 1");

  if(mysqli_num_rows($query) == 0) {
    return false;
  }

  $row = mysqli_fetch_array($query);

  $user_to = $row['user_to'];
  $user_from = $row['user_from'];

  if($user_to != $userLoggedIn) {
    return $user_to;
  } else {
    return $user_from;
  }
}

public function sendMessage($user_to, $body, $date) {

  if($body != "") {
    $userLoggedIn = $this->user_obj->getUsername();
    $query = mysqli_query($this->connection, "INSERT INTO messages VALUES(null, '$user_to', '$userLoggedIn', '$body', '$date', 'no', 'no', 'no')");
  }



}

public function getMessages($otherUser) {
  $userLoggedIn = $this->user_obj->getUsername();
  $data = "";

  $query = mysqli_query($this->connection, "UPDATE messages SET opened='yes' WHERE user_to='$userLoggedIn' AND user_from='$otherUser'");

  $get_messages_query = mysqli_query($this->connection, "SELECT * FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$otherUser') OR (user_from='$userLoggedIn' AND user_to='$otherUser')");

 while($row = mysqli_fetch_array($get_messages_query)) {
  $user_to = $row['user_to'];
  $user_from = $row['user_from'];
  $body = $row['body'];

  $div_top = ($user_to == $userLoggedIn) ? "<div class='message' id='light'>" : "<div class='message' id='dark'>";
  $data = $data . $div_top . $body . "</div><br>";

 }
  return $data;
}

public function getLatestMessage($userLoggedIn, $user2) {
  $details_array = array();
  $query = mysqli_query($this->connection, "SELECT body, user_to, date FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$user2') OR (user_to='$user2' AND user_from='$userLoggedIn') ORDER BY id DESC LIMIT 1");

  $row = mysqli_fetch_array($query);
  $sent_by = ($row['user_to'] == $userLoggedIn) ? "They said: " : "You said: ";

  $date_time = $row['date'];

         // Timeframe
         $date_time_now = date("Y-m-d H:i:s");
         $start_date = new DateTime($date_time); // time of post
         $end_date = new DateTime($date_time_now); // current time
         $interval = $start_date->diff($end_date); // Difference between dates

         if($interval->y >= 1) {
         if($interval == 1) {
           $time_message = $interval->y . " year ago";
         } else {
           $time_message = $interval->y . " years ago";
         }
         } else if ($interval->m >= 1) {
         if($interval->d == 0) {
         $days = " ago";
         } else if ($interval->d == 1) {
         $days = $interval->d . " day ago";
         }  else {
         $days = $interval->d . " days ago";
         }

         if ($interval->m == 1) {
         $time_message = " month ago" . $days;
         } else {
         $time_message = " months ago" . $days;
         } 
         } else if ($interval->d >= 1) {
         if ($interval->d == 1) {
           $time_message = "Yesterday";
         }  else {
           $time_message = $interval->d ." days ago";
         }
         } else if ($interval->h >= 1) {
         if($interval->h ==1) {
         $time_message = $interval->h ." hour ago";
         } else {
         $time_message= $interval->h ." hours ago";
         }
         } else if ($interval->i >= 1) {
         if ($interval->i == 1) {
           $time_message = $interval->i ." minute ago";
         } else {
           $time_message =  $interval->i ." minutes ago";
         }

         } else  {
         if ($interval->i < 30) {
           $time_message = "Just now";
         } else {
           $time_message = $interval->s . " seconds ago";
         }
         }

          array_push($details_array, $sent_by);
          array_push($details_array, $row['body']);
          array_push($details_array, $time_message);

          return $details_array;

}

public function getConversations() {
  $userLoggedIn = $this->user_obj->getUsername();
  $return_string = "";
  $conversations = array();

  $query = mysqli_query($this->connection, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC");

  while($row = mysqli_fetch_array($query)) {
    $user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

    if(!in_array($user_to_push, $conversations)){
      array_push($conversations, $user_to_push);
    }
  }

  foreach($conversations as $username) {
    $user_found_obj = new User($this->connection, $username);
    $latest_message_details = $this->getLatestMessage($userLoggedIn, $username);

    $dots = (strlen($latest_message_details[1]) >= 12 ) ? "..." : ""; // here we want the body, that is the second index (i=1) in array
    $split = str_split($latest_message_details[1], 12);
    $split = $split[0] . $dots;

    $return_string .= "<a href='messages.php?user=$username'> 
                      <div class='user_found_messages'>                     
                      <img src='" . $user_found_obj->getProfilePic() .  "' style='border-radius: 5px; margin-right: 5px;'>
                      " . $user_found_obj->getFirstAndLastName() . "
                      <span class='timestamp_smaller' id='grey'>" . $latest_message_details[2] . "</span>
                      <p id='grey' style='margin: 0;'>" . $latest_message_details[0] . $split . "</p>  
                      </div>                    
                      </a>";
  }

  return $return_string;
}



}



?>