<?php

require_once( 'model.php' );

/**
 * User
 */
class User extends Model {
    const TABLE_NAME = 'users';

    protected static $columns = array(
        'id' => 'int PRIMARY KEY NOT NULL AUTO_INCREMENT',
        'first_name' => 'VARCHAR(128) NOT NULL',
        'last_name' => 'VARCHAR(128) NOT NULL',
        'email' => 'VARCHAR(255) NOT NULL',
        'password' => 'VARCHAR(512) NOT NULL',  //for hashed password
        'role_id' => 'int NOT NULL DEFAULT 1'
    );

    protected static $constraints = '
        FOREIGN KEY (role_id) REFERENCES roles(id)
    ';

    public static function query_id_from_email($email) {
      $user_table = User::TABLE_NAME;
      $result = self::query(
        "SELECT id FROM $user_table WHERE email = '$email';"
      );
      $ids = array();
      foreach($result as $row) {
          $obj = new static ( $row );
          array_push($ids, $obj);
      }
      return $ids;
    }

    public static function query_all_with_membership() {
        $table = static::TABLE_NAME;
        $membership_table = Membership::TABLE_NAME;
        $columns_string = '';
        foreach ( static::$columns as $name => $type ) {
            $columns_string .= "$name,";
        }
        foreach ( Membership::$columns as $name => $type ) {
            $columns_string .= "$name,";
        }
        // Remove last comma
        $columns_string = substr($columns_string, 0, -1);

        $result = self::query(
            "SELECT $columns_string FROM $table AS u
                INNER JOIN $membership_table as m ON
                    u.id = m.user_id
            ;"
        );
        $retval = array();
        foreach ($result as $row) {
            $column_values = array();
            foreach (static::$columns as $name => $type) {
                if (is_int($name)) { continue; }
                $column_values[$name] = $row[$name];
            }
            foreach (Membership::$columns as $name => $type) {
                if (is_int($name) || $name == 'user_id') { continue; }
                $column_values[$name] = $row[$name];
            }
            $obj = new static(
                $column_values
            );
            array_push($retval, $obj);
        }
        return $retval;
    }

    public static function query_all_without_membership() {
        $table = static::TABLE_NAME;
        $membership_table = Membership::TABLE_NAME;
        $columns_string = '';
        foreach ( static::$columns as $name => $type ) {
            $columns_string .= "$name,";
        }
        foreach ( Membership::$columns as $name => $type ) {
            $columns_string .= "$name,";
        }
        // Remove last comma
        $columns_string = substr($columns_string, 0, -1);

        $result = self::query(
            "SELECT * FROM (
                SELECT user_id AS id, MAX(expiration_date) as expiration_date FROM
                    $membership_table GROUP BY id) AS i
                INNER JOIN $table AS u ON
                    u.id = i.id
                WHERE
                    i.expiration_date < CURDATE()
                ;"
        );
        $retval = array();
        foreach ($result as $row) {
            $column_values = array();
            foreach (static::$columns as $name => $type) {
                if (is_int($name)) { continue; }
                $column_values[$name] = $row[$name];
            }
            foreach (Membership::$columns as $name => $type) {
                if (is_int($name) || $name == 'user_id') { continue; }
                $column_values[$name] = $row[$name];
            }
            $obj = new static(
                $column_values
            );
            array_push($retval, $obj);
        }
        return $retval;
    }
}

class Membership extends Model {
    const TABLE_NAME = 'users_memberships';

    public static $columns = array(
        'user_id' => 'int NOT NULL',
        'expiration_date' => 'DATE NOT NULL'
    );

    protected static $constraints = '
        PRIMARY KEY (user_id, expiration_date),
        FOREIGN KEY (user_id) REFERENCES users(id)
    ';
}

?>
