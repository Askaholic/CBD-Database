
<header>
  <title>Sign up</title>
  <!-- ?php wp_head(); ? -->
</header>
<body>
<h1>Create your Contra Borealis Dance account</h1>

<script>
//can put other limits on password here
  function check()
  {
    if(!document.getElementsByName("password")[0].value)
    {
      document.getElementsByName("submit")[0].disabled = true;
      document.getElementsByName("bad_password")[0].innerHTML = "";
    }
    else if(document.getElementsByName("password")[0].value !== document.getElementsByName("confirm_password")[0].value)
    {
      document.getElementsByName("submit")[0].disabled = true;
      document.getElementsByName("bad_password")[0].style.color = "red";
      document.getElementsByName("bad_password")[0].innerHTML = "Passwords don't match.";
    }
    else
    {
      if(document.getElementsByName("password")[0].value.length >= 6)
      {
          document.getElementsByName("bad_password")[0].style.color = "green";
          document.getElementsByName("bad_password")[0].innerHTML = "    Password good.";
      } else
      {
        document.getElementsByName("bad_password")[0].style.color = "orange";
        document.getElementsByName("bad_password")[0].innerHTML = "Password must be at least 6 characters.";
      }

      if(checkInputs())
      {
        document.getElementsByName("submit")[0].disabled = false;
        document.getElementsByName("bad_input")[0].innerHTML = "";
      }
      else
      {
        document.getElementsByName("submit")[0].disabled = true;
        document.getElementsByName("bad_input")[0].style.color = "red";
        document.getElementsByName("bad_input")[0].innerHTML = "Missing input.";
      }
    }
  }

  function checkInputs()
  {
    if(!document.getElementsByName("first")[0].value || !document.getElementsByName("last")[0].value
        || !document.getElementsByName("email")[0].value || !document.getElementsByName("password")[0].value
        || !document.getElementsByName("confirm_password")[0].value)
          return false; //empty input field

    return true;
  }
</script>

<!--?php require_once(DP_PLUGIN_DIR . 'class.formbuilder.php');
 ?-->
<form method="post" action="signup">
  <?php wp_nonce_field('submit', 'signup_nonce');
    FormBuilder::input('text', 'first', 'First Name', 'onkeyup="check();" pattern="[A-Za-z]{2,128}" title="Enter First Name"');
    FormBuilder::input('text', 'last', 'Last Name', 'onkeyup="check();" pattern="[A-Za-z]{2,128}" title="Enter Last Name"');
    FormBuilder::input('email', 'email', 'Email Address', 'onkeyup="check();" pattern=".*[.]{1}[a-z]{2,4}" title="Enter valid Email Address"');
    FormBuilder::input('password', 'password', 'Password', 'onkeyup="check();" pattern=".{6,100}" title="Password must be from 6 to 100 characters in length"');
    FormBuilder::input('password', 'confirm_password', 'Confirm Password', 'onkeyup="check();"');
    //FormBuilder::input('submit", "submit", "", "", "width: 100px", "Sign Up");
     ?>
  <!-- <label for="first">First Name</label>
  <input type="text" name="first" onkeyup="check();" pattern="[A-Za-z]{2,128}" title="Enter First Name"> -->
  <!-- <label for="last">Last Name</label>
  <input type="text" name="last" onkeyup="check();" pattern="[A-Za-z]{2,128}" title="Enter Last Name">
  <label for="email">Email Address</label>
  <input type="email" name="email" onkeyup="check();" pattern=".*[.]{1}[a-z]{2,4}" title="Enter valid Email Address">
  <label for="password">Choose Password</label>
  <input type="password" name="password" onkeyup="check();" pattern=".{6,100}" title="Password must be from 6 to 100 characters in length">
  <label for="confirm_password">Confirm Password</label>
  <input type="password" name="confirm_password" onkeyup="check();"> -->
  <input type="submit" name="submit" value="Sign Up">
  <br><span name="bad_password"></span><br>
  <span name="bad_input"></span>
</form>

<!-- ?php wp_footer(); ? -->
</body>
