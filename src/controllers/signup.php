
<header>
  <title>Sign up for CBD</title>
  <?php wp_head(); ?>
</header>

<body>
<h1 style="margin-left: 10px;">Create your CBD account</h1>

<!-- form needs action php -->
<form method="post" action="signup">
  <input type="text" name="user" placeholder="Enter Username" style="width: 300px; margin-left: 10px;">
  <input type="text" name="email" placeholder="Enter Email Address" style="width: 300px; margin-left: 10px;">
  <input type="password" name="password" placeholder="Choose Password" style="width: 300px; margin-left: 10px;">
  <input type="password" name="confirm_password" placeholder="Confirm Password" style="width: 300px; margin-left: 10px;">
  <input type="submit" value="Sign Up" style="margin-left: 10px;">
</form>

<h1>User Data</h1>
<?php



    $test = "Hello Page";
    $name = $_POST['user'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $pass2 = $_POST['confirm_password'];
    if(!empty($name))
    {
      echo "<p> $name </p>";
      echo "<p> $email </p>";
      if($pass == $pass2)
      {
        echo "<p> $pass </p>";
      }
      else
      {
          echo "<p> non-matching passwords </p>";
      }
    }
    else
    {
        echo "<p> empty </p>";
    }



?>

  <?php wp_footer(); ?>
</body>
