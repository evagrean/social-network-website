<?php 
require 'config/config.php';
include("includes/classes/Message.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Notification.php");


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
  <title>Welcome to BrickBuddies</title>

  <!-- JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
  </script>
  <script src="assets/js/bootbox.min.js"></script>
  <script src="assets/js/brickbuddies.js"></script>
  <script src="assets/js/jquery.Jcrop.js"></script>
  <script src="assets/js/jcrop_bits.js"></script>


  <!-- CSS  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0&icon_names=delete" />
  <link rel="stylesheet" type="text/css" href="./assets/CSS/styles.css">
  <link rel="stylesheet" href="assets/CSS/jquery.Jcrop.css" type="text/css" />
</head>

<body>
  <div class="top-bar">
    <div class="logo">
      <a href="index.php">BrickBuddies</a>
    </div>

    <nav>

      <?php 
          // Unread messages
          $messages = new Message($connection, $userLoggedIn);
          $num_messages = $messages->getUnreadNumber();    

          // Unread notifications
          $notifications = new Notification($connection, $userLoggedIn);
          $num_notifications = $notifications->getUnreadNumber()    
        ?>

      <a href="" <?php echo  $userLoggedIn?>""><?php echo $user['first_name'] ?></i></a>
      <a href="index.php"><i class="material-icons">home</i></a>
      <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message' )">
        <i class="material-icons">mail</i>
        <?php 
       if ($num_messages > 0) {
        echo ' <span class="notification_badge" id="unread_message">' . $num_messages . '</span>'; 
       } 
       
        ?>

      </a>
      <a href="#"><i class="material-icons">notifications</i>
        <?php 
       if ($num_notifications > 0) {
        echo ' <span class="notification_badge" id="unread_notification">' . $num_notifications . '</span>'; 
       } 
       
        ?>
      </a>
      <a href="requests.php"><i class="material-icons">group</i></a>
      <a href="#"><i class="material-icons">settings</i></a>
      <a href="includes/handlers/logout.php"><i class="material-icons">logout</i></a>
    </nav>

    <div class="dropdown_data_window" style="height: 0px; border:none;">
      <input type="hidden" id="dropdown_data_type" value="">
    </div>
  </div>

  <script>
  $(function() {

    var userLoggedIn = '<?php echo $userLoggedIn; ?>';
    var dropdownInProgress = false;

    loadPosts(); //Load first posts


    $(".dropdown_data_window").scroll(function() {
      var bottomElement = $(".dropdown_data_window a").last();
      var noMorePosts = $('.dropdown_data_window').find('.noMoreDropdownData').val();

      // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
      if (isElementInView(bottomElement[0]) && noMoreData == 'false') {
        loadPosts();
      }
    });

    function loadPosts() {
      if (dropdownInProgress) { //If it is already in the process of loading some posts, just return
        return;
      }

      dropdownInProgress = true;

      // name of page to send ajax request to
      var pageName;
      var type = $('#dropdown_data_type').val();

      if (type == "notification") {
        pageName = "ajax_load_notifications.php";
      } else if (type = 'message') {
        pageName = "ajax_load_messages.php";
      }


      $.ajax({
        url: "includes/handlers/" + pageName,
        type: "POST",
        data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
        cache: false,

        success: function(response) {
          $('.dropdown_data_window').find('.nextPageDropdownData').remove(); //Removes current .nextpage 
          $('.dropdown_data_window').find('.noMoreDropdownData').remove(); //Removes current .nextpage 

          $('.dropdown_data_window').append(response);

          dropdownInProgress = false;
        }
      });
    }

    //Check if the element is in view
    function isElementInView(el) {
      var rect = el.getBoundingClientRect();

      return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
      );
    }
  });
  </script>


  <div class="wrapper">