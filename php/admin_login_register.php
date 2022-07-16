<?php
include('Examination.php');

$exam = new Examination;

// if login => go index page.
$exam->admin_session_public();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- main.css -->
  <link rel="stylesheet" href="../assets/css/login_register.css" />

  <!-- vendor.js -->
  <script src="../assets/js/vendor.js" defer></script>

  <!-- app.js -->
  <script src="../assets/js/app.js" defer></script>

  <title>Online Examination System</title>
</head>

<body>
  <div class="container">
    <!-- login -->
    <form method="POST" class="form" id="login">
      <h1 class="form__title">Login</h1>
      <div class="form__message form__message--errror"></div>

      <div class="form__input-group">
        <input type="text" class="form__input" id="login_admin_email_address" placeholder="Email Address" />
        <div class="form__input-error-message"></div>
      </div>

      <div class="form__input-group">
        <input type="password" class="form__input" id="login_admin_password" autocomplete="off"
          placeholder="Password" />
        <div class="form__input-error-message"></div>
      </div>

      <button class="form__button" id="loginBtn" type="submit">
        Continue
      </button>

      <p class="form__text">
        <a href="#" class="form__link">Forgot your password?</a>
      </p>

      <p class="form__text">
        <a href="#" class="form__link" id="linkCreateAccount">Don't have an account? Create account</a>
      </p>
    </form>

    <!-- createAccount -->
    <form class="form form--hidden" id="createAccount">
      <h1 class="form__title">Create Account</h1>
      <div class="form__message form__message--errror"></div>

      <div class="form__input-group">
        <input type="text" class="form__input" id="register_admin_username" autofocus autocomplete="off"
          placeholder="Username" />
        <div class="form__input-error-message"></div>
      </div>

      <div class="form__input-group">
        <input type="text" class="form__input" id="register_admin_email_address" name="admin_email_address" autofocus
          autocomplete="off" placeholder="Email Address" />
        <div class="form__input-error-message"></div>
      </div>

      <div class="form__input-group">
        <input type="password" class="form__input" id="register_admin_password" name="admin_password" autocomplete="off"
          placeholder="Password" />
        <div class="form__input-error-message"></div>
      </div>

      <div class="form__input-group">
        <input type="password" class="form__input" id="register_admin_confirm_password" name="confirm admin_password"
          autocomplete="off" placeholder="Confirm Password" />
        <div class="form__input-error-message"></div>
      </div>

      <button class="form__button" id="createAccountBtn" type="submit">
        Continue
      </button>

      <p class="form__text">
        <a href="#" class="form__link" id="linkLogin">Already have an account? Sign in</a>
      </p>
    </form>
  </div>
</body>

</html>