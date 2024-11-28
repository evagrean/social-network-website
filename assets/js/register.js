//jQuery can only be used when the document is loaded

$(document).ready(function () {
  // on click register, hide login and show registeration form
  $("#register").click(function () {
    $("#login-form").slideUp("slow", function () {
      $("#register-form").slideDown("slow");
    });
  });

  // on click login, hide register and show login form
  $("#login").click(function () {
    $("#register-form").slideUp("slow", function () {
      $("#login-form").slideDown("slow");
    });
  });

  // keep register form if error messages for fields
  $("#reg-button").click(function () {
    if (this.id == "reg-button") {
      $("#login-form").hide();
      $("#register-form").show();
    }
  });
});

// <?php
// if(isset($_POST['reg_button'])) {
//   echo '
//   <script>
//   $(document).ready(function() {
//   $("#login-form").hide();
//   $("#register-form").show();

//   });
//   </script>

//   ';
// }
// ?>
