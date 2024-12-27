<?php 
include("includes/header.php");
// include("includes/classes/Message.php");
// include("includes/classes/User.php");


$message_obj = new Message($connection, $userLoggedIn);

if(isset($_GET['user'])) {
  $user_to = $_GET['user'];
} else {
  $user_to = $message_obj->getMostRecentUser();
  if($user_to == false) {
    $user_to = 'new';
  }
}

if($user_to != "new"){
  $user_to_obj = new User($connection, $user_to);
}

if(isset($_POST['post_message'])) {

if(isset($_POST['message_body'])) {
  $body = mysqli_real_escape_string($connection, $_POST['message_body']); //prepares the string so that it can be used in SQL statement
  $date = date("Y-m-d H:i:s");
  $message_obj->sendMessage($user_to, $body, $date);
  header("Location: messages.php?user=" . $user_to); // added to prevent re-sending last message again after every page refresh
}
}


?>

<div class="user_details column">
  <a href="<?php echo  $userLoggedIn?>"><img src="<?php echo $user['profile_pic']; ?>" alt="profile-pic"></a>
  <div class="user_details_left_right">
    <a href="<?php echo  $userLoggedIn?>" site"><?php echo $user['first_name'] . " " . $user['last_name'];?></a>

    <?php echo "Posts: " . $user['num_posts'] . "<br/>"; 

  echo "Likes: " . $user['num_likes'];?>
  </div>
</div>

<div class="main_column column" id="main_column">
  <?php 
  if($user_to != "new") {
    echo "<h4>You and <a href='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a></h4><hr><br>";

    echo "<div class='loaded_messages' id='scroll_messages'>";
    echo $message_obj->getMessages($user_to);
    echo "</div>";

  } else {
    echo "<h4>New Message</h4>";
  }
  
  ?>


  <div class="message_post">
    <form action="" method="POST">
      <?php 
      if($user_to == "new") {
        echo "Select the friend you would like to message <br><br>";
        ?>
      To: <input type='text' onkeyup='getUsers(this.value, "<?php echo $userLoggedIn; ?>")' name='q' placeholder='Name'
        autocomplete='off' id='search_text_input'>
      <?php
      echo "<div class='results'></div>";
      } else {
      echo "<textarea name='message_body' id='message_textarea' placeholder='Write your message here ...'></textarea>";
      echo "<input type='submit' name='post_message' id='message_submit' value='Send'>";
      }
      ?>
    </form>
  </div>

  <script>
  // makes that when message is sent or page reloaded, it scrolls to most recent message
  var div = document.getElementById("scroll_messages");


  if (div != null) {
    div.scrollTop = div.scrollHeight;
  }
  </script>


</div>
<div class="user_details column" id="conversations">
  <h4>Conversations</h4>
  <div class="loaded_conversations">
    <?php echo $message_obj->getConversations()?>
  </div>
  <br>
  <a href="messages.php?user=new">New Message</a>

</div>