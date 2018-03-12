<?php

require_once( 'model.php' );

class Token extends Model {
    const TABLE_NAME = 'tokens';

    public static $columns = array(
        'user_id' => 'int NOT NULL PRIMARY KEY',
        'recovery_token' => 'VARCHAR(32) NOT NULL',
        'expiration_date' => 'DATETIME NOT NULL'
    );
    
    protected static $constraints = '
        FOREIGN KEY (user_id) references users(id)
    ';

    public static function query_token($token) {
      $token_table = Token::TABLE_NAME;
      $result = self::query(
        "SELECT * FROM $token_table WHERE recovery_token = '$token';"
      );
      $tokens = array();
      foreach($result as $row) {
          $obj = Token::create_instance_from_row( $row );
          array_push($tokens, $obj);
      }
      return $tokens;
    }

}

?>
