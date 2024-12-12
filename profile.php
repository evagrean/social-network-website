<?php 
include('includes/header.php');
include('includes/classes/User.php');
include('includes/classes/Post.php');


if(isset($_GET['profile_username'])) {
$username = $_GET['profile_username'];
$user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$username'");
$user_array = mysqli_fetch_array($user_details_query);

$num_friends = (substr_count($user_array['friend_array'], ",")) -1;
}

if (isset($_POST['remove_friend'])) {
  $user = new User($connection, $userLoggedIn);
  $user->removeFriend($username);
  echo "remove friend clicked";
 
          }

if (isset($_POST['add_friend'])) {
  $user = new User($connection, $userLoggedIn);
  $user->sendRequest($username);
  echo "add friend clicked";
 
          }

if (isset($_POST['respond_request'])) {
header("Location: requests.php");
          }


?>
<style>
@media (min-width: 950px) and (max-width: 5000px) {
  .wrapper {
    margin-left: 0;
    padding-left: 0;
  }

}
</style>

<div class="profile_left">
  <img src="<?php echo $user_array['profile_pic'];?>" alt="profile picture">

  <div class="profile_info">
    <p><?php echo "Posts: " . $user_array['num_posts']; ?></p>
    <p><?php echo "Likes: " . $user_array['num_likes']; ?></p>
    <p><?php echo "Friends: " . $num_friends; ?></p>

  </div>

  <form action="<?php echo $username;?>" method="POST">
    <?php 
        $profile_user_obj = new User($connection, $username);

        if ($profile_user_obj->isClosed()) {
          header("Location: user_closed.php");
        }

        $logged_in_user_obj = new User($connection, $userLoggedIn);
        
        if ($userLoggedIn != $username) {

          if ($logged_in_user_obj->isFriend($username)) {

            echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
          } else if ($logged_in_user_obj->didReceiveRequest($username)) {
            echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
          } else if ($logged_in_user_obj->didSendRequest($username)) {
            echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
          } else {
            echo '<input type="submit" name="add_friend" class="success" value="Add friend"><br>';
          }

        }
              
    ?>



  </form>
  <input type="submit" class="post_modal_btn" data-bs-toggle="modal" data-bs-target="#post_modal"
    value="Post Something">

</div>

<div class="main_column column">
  <?php echo $username?>


</div>






</div>
<!-- Modal -->
<div class="modal fade" id="post_modal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Post something</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>This will appear on the user's profile page and also their newsfeed for your friends to see</p>
        <form class="profile_post" action="" method="POST">
          <div class="form-group">
            <textarea class="form-control" name="post_body"> </textarea>
            <input type="hidden" name="user_from" value="<?php echo $userLoggedIn;?>">
            <input type="hidden" name="user_to" value="<?php echo $username;?>">

          </div>
        </form>
      </div>



      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

<!-- What htaccess is doing:
If someone puts anything in the URL (and the text contains upper or lower case letters and numbers) 
it will replace this with "profile.php?profile_username and go to this site -->