<?php 
    require 'config/config.php';
    include('includes/classes/User.php');
    include('includes/classes/Post.php');

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
  <title>Comment</title>
  <link rel="stylesheet" type="text/css" href="./assets/CSS/styles.css">
</head>

<body>

  <style type="text/css">
  * {
    font-size: 12px;
    font-family: Arial, Helveticca, Sans-serif;
  }
  </style>



  <script>
  function toggle() {
    let element = document.getElementById("comment_section");

    if (element.style.display = "block") {
      element.style.display = "none";
    } else {
      element.style.display = "block";
    }
  }
  </script>

  <?php 
  // get id of post
  if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
  }

  $user_query = mysqli_query($connection, "SELECT added_by, user_to FROM posts WHERE id ='$post_id'");
  $row = mysqli_fetch_array($user_query);
  $posted_to = $row['added_by'];
  $user_to = $row['user_to'];

  if (isset($_POST['postComment' . $post_id])) {
    $post_body = $_POST['post_body'];
    $post_body = mysqli_escape_string($connection, $post_body);
    $date_time_now = date("Y-m-d H:i.s");
    $insert_post = mysqli_query($connection, "INSERT INTO comments VALUES(null, '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id')");
    // handle notification on comment 
    if ($posted_to != $userLoggedIn) {
      $notification = new Notification($this->connection, $userLoggedIn);
      $notification->insertNotification($returned_id, $posted_to, "comment");
    } 
    
    if ($user_to != 'none' && $user_to != $userLoggedIn ) {
      $notification = new Notification($this->connection, $userLoggedIn);
      $notification->insertNotification($returned_id, $user_to, "profile_comment");
    }



    echo "<p>Comment Posted!</p>";
  }
  
  
  ?>

  <form action="comment_frame.php?post_id=<?php echo $post_id;?>" id="comment_form"
    name="postComment<?php echo $post_id; ?>" method="POST">
    <textarea name="post_body"></textarea>
    <input type="submit" name="postComment<?php echo $post_id; ?>" value="Posten">
  </form>


  <!-- Load Comments -->

  <?php 
  
  $get_comments = mysqli_query($connection, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
  $count = mysqli_num_rows($get_comments);

  if ($count != 0) {
    while($comment = mysqli_fetch_array($get_comments)) {
      $comment_body = $comment['post_body'];
      $posted_to = $comment['posted_to'];
      $posted_by = $comment['posted_by'];
      $date_added = $comment['date_added'];
      $removed = $comment['removed'];
     // [$comment_body,  $posted_to,  $potsed_by, $date_added, $removed ] = $comment;

             // Timeframe
             $date_time_now = date("Y-m-d H:i:s");
             $start_date = new DateTime($date_added); // time of post
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

             $user_obj = new User($connection, $posted_by);

             ?>
  <div class="comment-section">
    <a href="<?php echo $posted_by?>" target="_parent">
      <img src="<?php echo $user_obj->getProfilePic();?>" alt="profile picture" title="<?php echo $posted_by ?>"
        style="float: left;" height=30>
    </a>
    <a href="<?php echo $posted_by?>" target="_parent">
      <b><?php echo $user_obj->getFirstAndLastName();?></b>
    </a>
    &nbsp; &nbsp; &nbsp; &nbsp;
    <?php echo $time_message . "<br>" . $comment_body;?>
    <br>
    <br>
    <hr>
  </div>

  <?php 


    }
  }

  else {
    echo "<center><br><br>Noch keine Kommentare!</center>";
  }

  ?>




</body>

</html>