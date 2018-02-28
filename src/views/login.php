<!-- TODO may need to change styling to be consistant with page, waiting on client code -->

<header>
  <title>Login</title>
  <!-- ?php wp_head(); ? -->
</header>

<body>
<h1 style="margin-left: 10px;">Login to your Contra Borealis Dance account</h1>

<form method="post" action="login">
  <?php wp_nonce_field('submit', 'login_nonce'); ?>
  <input type="email" name="email" id="email" title="user@domain.com"
   placeholder="Enter Email Address" style="width: 300px; margin-left: 10px;">
  <input type="password" id="password" name="password"
   placeholder="Enter Password" style="width: 300px; margin-left: 10px;">
  <input type="submit" id="submit" value="Login" style="margin-left: 10px;">
  <span id="bad_password"></span><br>
  <span id="bad_input"></span>
</form>

<!-- ?php wp_footer(); ? -->
</body>
