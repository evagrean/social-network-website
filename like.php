<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Likes</title>
  <link rel="stylesheet" type="text/css" href="./assets/CSS/styles.css">
</head>

<body>

  <style type="text/css">
  * {
    font-size: 12px;
    font-family: Arial, Helveticca, Sans-serif;
  }

  body {
    background-color: #fff;

  }

  form {
    position: absolute;
    top: 0;
  }

  /* .comment_like:hover {
    cursor: pointer;
  } */
  </style>

  <?php 
    require 'config/config.php';
    include('includes/classes/User.php');
    include('includes/classes/Post.php');
    include('includes/classes/Notification.php');

    if(isset($_SESSION['username'])) {
      $userLoggedIn = $_SESSION['username'];
      $user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$userLoggedIn'");
      // returns all the columns in the table as an array
      $user = mysqli_fetch_array($user_details_query);
    } else {
      header('Location: register.php');
    }

      // get id of post
  if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

  }

  $get_likes = mysqli_query($connection, "SELECT likes, added_by from posts WHERE id='$post_id'");
  $row = mysqli_fetch_array($get_likes);
 
  $total_likes = $row['likes']; 
  $user_liked = $row['added_by'];


  $user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$user_liked'");
  $row = mysqli_fetch_array($user_details_query);
  $total_user_likes = $row['num_likes'];

  

  // Like button

  if (isset($_POST['like_button'])) {
    $total_likes++;   
    $query = mysqli_query($connection, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
    $total_user_likes++;
    $user_likes = mysqli_query($connection, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
    $insert_user = mysqli_query($connection, "INSERT INTO likes VALUES(null, '$userLoggedIn', '$post_id')");
  
    // Insert notification

    if ($user_liked != $userLoggedIn) {
      $notification = new Notification($connection, $userLoggedIn);
      $notification->insertNotification($post_id, $user_liked, "like");
    }
  
  }

  // Unlike button
  if (isset($_POST['unlike_button'])) {
    $total_likes--;    
    $query = mysqli_query($connection, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
    $total_user_likes--;
    $user_likes = mysqli_query($connection, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
    $insert_user = mysqli_query($connection, "DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");

  }
  // Check for previous likes
  $check_query = mysqli_query($connection, "SELECT * from likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
  $num_rows = mysqli_num_rows($check_query);

  if($num_rows > 0) {
    echo '<form action="like.php?post_id=' . $post_id . '" method="POST">
           <input type="submit" class="comment_like" name="unlike_button" value="Unlike">
            <div class="like_value">
          ' . $total_likes . ' Likes
            </div>
          </form>
    ' ;
  } else {
    echo '<form action="like.php?post_id=' . $post_id . '" method="POST">
            <input type="submit" class="comment_like" name="like_button" value="Like">
            <div class="like_value">
            ' . $total_likes . ' Likes
            </div>
          </form>
';
  }

    ?>

</body>

</html>