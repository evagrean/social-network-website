<?php 
require '../../config/config.php';
include('../classes/User.php');
include('../classes/Post.php');

if(isset($_POST['post_body'])) {



  $post = new Post($connection, $_POST['user_from']);
  $post->submitPost($_POST['post_body'], $_POST['user_to']);

}

?>