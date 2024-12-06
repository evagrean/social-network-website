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

public function loadPostsFriends($data, $limit) {

$page = $data['page'];
$userLoggedIn = $this->user_obj->getUsername();

if($page == 1) {
  $start = 0;
} else {
  $start = ($page-1) * $limit;
}


  $str = ""; // string to return
  $data_query = mysqli_query($this->connection, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

if (mysqli_num_rows($data_query) > 0) {

  $num_iterations = 0; // number of results checked 
  $count = 1;



    while($row = mysqli_fetch_array($data_query)) {
    $id = $row['id'];
    $body = $row['body'];
    $added_by = $row['added_by'];
    $date_time = $row['date_added'];

    // Prepare user_to string so it can be included eve if not posted to a user
    if($row['user_to'] == "none") {
    $user_to = "";
    } else {
    $user_to_obj = new User($this->connection, $row['user_to']);
    $user_to_name = $user_to_obj->getFirstAndLastName();
    $user_to = " to <a href='" . $row['user_to'] . "'>" . $user_to_name . "</a>";
    }

    // Check if user who posted has their account closed and dont show their posts
    $added_by_obj = new User($this->connection, $added_by);

    if($added_by_obj->isClosed()) {
    continue;
    }

   $user_logged_obj = new User($this->connection, $userLoggedIn);
   
        if ($user_logged_obj->isFriend($added_by)) {

        

          if($num_iterations++ < $start) {
      continue;
          }

          // once 10 posts have been loaded, break
          if($count > $limit) {
            break;
          } else {
            $count++;
          }

          $user_details_query = mysqli_query($this->connection, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
          $user_row = mysqli_fetch_array($user_details_query);
          $first_name = $user_row['first_name'];
          $last_name = $user_row['last_name'];
          $profile_pic = $user_row['profile_pic'];

          // Timeframe
          $date_time_now = date("Y-m-d H:i:s");
          $start_date = new DateTime($date_time); // time of post
          $end_date = new DateTime($date_time_now); // current time
          $interval = $start_date->diff($end_date); // Difference between dates

          if($interval->y >= 1) {
          if($interval == 1) {
            $time_message = "Vor " . $interval->y . " Jahr";
          } else {
            $time_message = "Vor " . $interval->y . " Jahren";
          }
          } else if ($interval->m >= 1) {
          if($interval->d == 0) {
          $days = "";
          } else if ($interval->d == 1) {
          $days = " und" . $interval->d . " Tag";
          }  else {
          $days = " und " . $interval->d . " Tagen";
          }

          if ($interval->m == 1) {
          $time_message = "Vor " . $interval->m . " Monat" . $days;
          } else {
          $time_message = "Vor " . $interval->m . " Monaten" . $days;
          } 
          } else if ($interval->d >= 1) {
          if ($interval->d == 1) {
            $time_message = "Gestern";
          }  else {
            $time_message = "Vor " . $interval->d . " Tagen";
          }
          } else if ($interval->h >= 1) {
          if($interval->h ==1) {
          $time_message = "Vor " . $interval->h . " Stunde";
          } else {
          $time_message= "Vor " . $interval->h .  " Stunden";
          }
          } else if ($interval->i >= 1) {
          if ($interval->i == 1) {
            $time_message = "Vor " . $interval->i . " Minute";
          } else {
            $time_message = "Vor " . $interval->i . " Minuten";
          }

          } else  {
          if ($interval->i < 30) {
            $time_message = "Gerade eben";
          } else {
            $time_message = "Vor " . $interval->i . " Sekunden";
          }
          }

          $str .= "<div class='status_post'>
                  <div class='post_profile_pic'>
                  <img src='$profile_pic' width='50'>
                  </div>
                  <div class='post_container'>
                  <div class='posted_by' style='color:#ACACAC;'>
                  <a href='$added_by'>$first_name $last_name</a>$user_to
                  </div>
                  <div style='color:#ACACAC;'>$time_message</div>
                  <div id='post_body'>
                  $body<br></div>
                  </div>
                  </div><hr>";


                }



    }

    if($count > $limit) {
      $str .= "<input type='hidden' class='nextPage' value='" . ($page+1) . "'>
      <input type='hidden' class='noMorePosts' value='false'>";
    } else {
      $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;'>Keine weiteren Posts!</p>";
    }
}
  echo $str;
}

}



?>