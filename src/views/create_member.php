<header>
  <title>Create member</title>
  <?php wp_head(); ?>
</header>

<body>
<h1 style="margin-left: 10px;">Create a new Contra Borealis Dance account</h1>

<form method="post" action="create_member">
  <?php wp_nonce_field('submit', 'create_member_nonce'); ?>
  <input type="text" name="user"
   pattern="[A-Za-z]{2,128} [A-Za-z]{2,128}" title="John Doe"
   placeholder="Enter First and Last Name" style="width: 300px; margin-left: 10px;">
  <input type="email" name="email"
    placeholder="Enter Email Address" style="width: 300px; margin-left: 10px;">
  <input type="date" name="expiry" style="width: 300px; margin-left: 10px;">
  <input type="submit" id="submit" value="Create Member" style="margin-left: 10px;">
</form>

<?php wp_footer(); ?>
</body>