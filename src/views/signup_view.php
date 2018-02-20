<!-- TODO may need to change styling to be consistant with page, waiting on client code -->

<header>
  <title>Sign up</title>
  <!-- ?php wp_head(); ? -->
</header>

<body>
<h1 style="margin-left: 10px;">Create your Contra Borealis Dance account</h1>

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
      document.getElementById("bad_password").style.color = "green";
      document.getElementById("bad_password").innerHTML = "    Password good.";
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

<form method="post" action="signup">
  <?php wp_nonce_field('submit', 'signup_nonce'); ?>
  <input type="text" name="first" id="first" onkeyup="check();"
   pattern="[A-Za-z]{2,128}" title="First name"
   placeholder="Enter First Name" style="width: 300px; margin-left: 10px;">
   <input type="text" name="last" id="last" onkeyup="check();"
    pattern="[A-Za-z]{2,128}" title="Last name"
    placeholder="Enter Last Name" style="width: 300px; margin-left: 10px;">
  <input type="email" name="email" id="email" onkeyup="check();"
    pattern=".*[.]{1}[a-z]{2,4}" title="user@domain.com"
    placeholder="Enter Email Address" style="width: 300px; margin-left: 10px;">
  <input type="password" id="password" name="password" onkeyup="check();"
  pattern=".{6,100}" title="Password must be from 6 to 100 characters in length."
  placeholder="Choose Password" style="width: 300px; margin-left: 10px;">
  <input type="password" id="confirm_password" name="confirm_password" onkeyup="check();"
   placeholder="Confirm Password" style="width: 300px; margin-left: 10px;">
  <input type="submit" id="submit" value="Sign Up" style="margin-left: 10px;">
  <span id="bad_password"></span><br>
  <span id="bad_input"></span>
</form>

<!-- ?php wp_footer(); ? -->
</body>
