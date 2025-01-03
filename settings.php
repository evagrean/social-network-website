<?php 
include("includes/header.php");
include("includes/form_handlers/settings_handler.php");
?>

<div class="main_column column">

  <h4>Account Settings</h4>
  <?php 
echo "<img src='" . $user['profile_pic'] . "' id='small_profile_pic'>";
?>
  <br>
  <a href="upload.php">Upload new profile picture</a><br><br><br>
  <p> Modify the values and click 'Update Details'</p>


  <?php 
  $user_data_query = mysqli_query($connection, "SELECT first_name, last_name, email FROM users WHERE username='$userLoggedIn'");
  $row = mysqli_fetch_array($user_data_query);

  $first_name = $row['first_name'];
  $last_name = $row['last_name'];
  $email = $row['email'];
  
  ?>

  <form action="settings.php" method='POST'>
    First Name: <input type="text" name="first_name" value="<?php echo $first_name;?>" class="settings_input"><br>
    Last Name: <input type="text" name="last_name" value="<?php echo $last_name;?>" class="settings_input"><br>
    Email: <input type="text" name="email" value="<?php echo $email;?>" class="settings_input"><br>

    <?php echo $message;?>

    <input type="submit" name="update_details" id="save_details_button" value="Update Details"
      class="info settings_submit"><br>
  </form>

  <h4>Change Password</h4>
  <form action="settings.php" method='POST'>
    Old Password: <input type="password" name="old_password" class="settings_input"><br>
    New Password: <input type="password" name="new_password_1" class="settings_input"> <br>
    New Password Confirm: <input type="password" name="new_password_2" class="settings_input"><br>

    <?php echo $password_message;?>

    <input type="submit" name="update_password" id="save_password_button" value="Update Password"
      class="info settings_submit"><br>
  </form>

  <h4>Close Account</h4>
  <form action="settings.php" method="POST">
    <input type="submit" name="close_account" id="close_account_button" value="Close Account"
      class="danger settings_submit">
  </form>

</div>