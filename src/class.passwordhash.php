<?php

//using php default hash functions
// http://php.net/manual/en/function.password-hash.php

class Password
{

  function hash($pass)
  {
    return password_hash($pass, PASSWORD_DEFAULT);
  }

  function verify($unhashed, $hashed)
  {
    return password_verify($unhashed, $hashed);
  }

}



 ?>
