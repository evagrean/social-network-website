<?php 
include('includes/header.php');
include('includes/classes/User.php');
include('includes/classes/Post.php');


if(isset($_POST['post'])) {
  $post = new Post($connection, $userLoggedIn);
  $post->submitPost($_POST['post_text'], 'none');
  header("Location: index.php"); // refreshes the page after submit 
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
<div class="main_column column">
  <form action="index.php" method="POST" class="post_form">
    <textarea name="post_text" id="post_text" placeholder="Was gibt es Neues?"></textarea>
    <input type="submit" name="post" id="post_button" value="Posten">




  </form>
  <hr>

  <?php 
  
  $post = new Post($connection, $userLoggedIn);
  $post->loadPostsFriends();
  
  ?>





</div>


</div>
</body>

</html>