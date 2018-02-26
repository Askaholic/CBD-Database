
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
    if(!document.getElementById("password").value)
    {
      document.getElementById("submit").disabled = true;
      document.getElementById("bad_password").innerHTML = "";
    }
    else if(document.getElementById("password").value !== document.getElementById("confirm_password").value)
    {
      document.getElementById("submit").disabled = true;
      document.getElementById("bad_password").style.color = "red";
      document.getElementById("bad_password").innerHTML = "Passwords don't match.";
    }
    else
    {
      if(document.getElementById("password").value.length >= 6)
      {
          document.getElementById("bad_password").style.color = "green";
          document.getElementById("bad_password").innerHTML = "    Password good.";
      } else
      {
        document.getElementById("bad_password").style.color = "orange";
        document.getElementById("bad_password").innerHTML = "Password must be at least 6 characters.";
      }

      if(checkInputs())
      {
        document.getElementById("submit").disabled = false;
        document.getElementById("bad_input").innerHTML = "";
      }
      else
      {
        document.getElementById("bad_input").style.color = "red";
        document.getElementById("bad_input").innerHTML = "Missing input.";
      }
    }
  }

  function checkInputs()
  {
    if(!document.getElementById("first").value || !document.getElementById("last").value
        || !document.getElementById("email").value || !document.getElementById("password").value
        || !document.getElementById("confirm_password").value)
          return false; //empty input field

    return true;
  }
</script>

<?php require_once(DP_PLUGIN_DIR . 'class.formbuilder.php');
 ?>
<form id="form" method="post" action="signup">
  <?php wp_nonce_field('submit', 'signup_nonce');
    // FormBuilder::input("text", "first", "First Name", "check();", "[A-Za-z]{2,128}",
    //                   "Enter First Name");
    // FormBuilder::input("text", "last", "Last Name", "check();", "[A-Za-z]{2,128}",
    //                   "Enter Last Name");
    // FormBuilder::input("email", "email", "Email Address", "check();", ".*[.]{1}[a-z]{2,4}",
    //                   "Enter valid Email Address");
    // FormBuilder::input("password", "password", "Password", "check();", ".{6,100}",
    //                 "Password must be from 6 to 100 characters in length");
    // FormBuilder::input("password", "confirm_password", "Confirm Password", "check();");
    // FormBuilder::input("submit", "submit", "", "", "width: 100px", "Sign Up");
     ?>
  <label for="first">First Name</label>
  <input class="inputField" type="text" name="first" id="first" onkeyup="check();"
   pattern="[A-Za-z]{2,128}" title="Enter First Name">
  <label for="last">Last Name</label>
  <input type="text" name="last" id="last" class="inputField" onkeyup="check();"
    pattern="[A-Za-z]{2,128}" title="Enter Last Name">
  <label for="email">Email Address</label>
  <input type="email" name="email" id="email" class="inputField" onkeyup="check();"
    pattern=".*[.]{1}[a-z]{2,4}" title="Enter valid Email Address">
  <label for="password">Choose Password</label>
  <input type="password" id="password" name="password" class="inputField" onkeyup="check();"
  pattern=".{6,100}" title="Password must be from 6 to 100 characters in length">
  <label for="confirm_password">Confirm Password</label>
  <input type="password" id="confirm_password" name="confirm_password" class="inputField" onkeyup="check();">
  <input type="submit" id="submit" class="inputButton" value="Sign Up">
  <br><span id="bad_password"></span><br>
  <span id="bad_input"></span>
</form>

<!-- ?php wp_footer(); ? -->
</body>
