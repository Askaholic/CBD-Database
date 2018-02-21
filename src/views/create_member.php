<header>
  <title>Create member</title>
  <?php wp_head(); ?>
</header>

<body>
<h1 style="margin-left: 10px;">Create a new Contra Borealis Dance account</h1>

<script>
//can put other limits on password here
  function check()
  {
    if(checkInputs())
    {
      document.getElementById("submit").disabled = false;
      document.getElementById("bad_input").innerHTML = "";
    }
    else
    {
      document.getElementById("submit").disabled = false;
    }
  }

  function checkInputs()
  {
    if(!document.getElementById("first").value || !document.getElementById("last").value
        || !document.getElementById("email").value || !document.getElementById("expiry").value)
        return false; // empty input field
    return true;
  }
</script>

<form method="post" action="create_member">
  <?php wp_nonce_field('submit', 'create_member_nonce'); ?>
  <input type="text" name="first" id="first" onkeyup="check();"
    pattern="[A-Za-z]{2,128}" title="First name"
    placeholder="Enter First Name" style="width: 300px; margin-left: 10px;">
  <input type="text" name="last" id="last" onkeyup="check();"
    pattern="[A-Za-z]{2,128}" title="Last name"
    placeholder="Enter Last Name" style="width: 300px; margin-left: 10px;">
  <input type="email" name="email" id="email" onkeyup="check();"
    pattern=".*[.]{1}[a-z]{2,4}" title="user@domain.com"
    placeholder="Enter Email Address" style="width: 300px; margin-left: 10px;">
  <input type="date" name="expiry" id="expiry" onkeyup="check();"
    style="width: 300px; margin-left: 10px;">
  <input type="submit" id="submit" value="Create Member" style="margin-left: 10px;">
</form>

<?php wp_footer(); ?>
</body>