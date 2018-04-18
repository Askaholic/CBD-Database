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

    public static function query_users_from_email($email) {
      $user_table = User::TABLE_NAME;
      $result = self::query(
        "SELECT * FROM $user_table WHERE email = '$email';"
      );
      $users = array();
      foreach($result as $row) {
          $obj = User::create_instance_from_row( $row );
          array_push($users, $obj);
      }
      return $users;
    }

    public static function query_users_from_id($id) {
        $user_table = User::TABLE_NAME;
        $result = self::query(
          "SELECT * FROM $user_table WHERE id = '$id';"
        );
        $users = array();
        foreach($result as $row) {
            $obj = User::create_instance_from_row( $row );
            array_push($users, $obj);
        }
        return $users;
    }

    public static function query_all_with_membership() {
        $table = static::TABLE_NAME;
        $membership_table = Membership::TABLE_NAME;
        $columns_string = '';
        foreach ( static::$columns as $name => $type ) {
            $columns_string .= "u.$name,";
        }
        foreach ( Membership::$columns as $name => $type ) {
            $columns_string .= "m.$name,";
        }
        // Remove last comma
        $columns_string = substr($columns_string, 0, -1);

        $result = self::query(
            "SELECT $columns_string FROM $table AS u
                INNER JOIN $membership_table as m ON
                    u.id = m.user_id
                WHERE m.expiration_date < CURDATE()
                ORDER By u.first_name
            ;"
        );
        $retval = array();
        foreach ($result as $row) {
            $obj = self::create_instance_from_row_with_membership( $row );
            array_push($retval, $obj);
        }
        return $retval;
    }

    public static function query_all_users() {
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
                RIGHT OUTER JOIN $table AS u ON
                    u.id = i.id;"
        );
        $retval = array();
        foreach ($result as $row) {
            $obj = self::create_instance_from_row_with_membership( $row );
            array_push( $retval, $obj );
        }
        return $retval;
    }

    protected static function create_instance_from_row_with_membership( $row ) {
        $column_values = array();
        foreach ( static::$columns as $name => $type ) {
            if ( is_int( $name ) ) { continue; }
            $column_values[$name] = $row[$name];
        }
        foreach ( Membership::$columns as $name => $type ) {
            if ( is_int( $name ) || $name == 'user_id' ) { continue; }
            $column_values[$name] = $row[$name];
        }
        $obj = new static(
            $column_values
        );
        return $obj;
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

class UserInvoices extends Model {
    const TABLE_NAME = 'user_invoices';

    public static $columns = array(
        'id' => 'int PRIMARY KEY NOT NULL AUTO_INCREMENT',
        'user_id' => 'int NOT NULL',
        'scheduled_event_id' => 'int NOT NULL',
        'guest_amount' => 'int',
        'invoice_amount' => 'DOUBLE',
        'amount_paid' => 'DOUBLE',
    );

    protected static $constraints = '
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (scheduled_event_id) REFERENCES scheduled_events(id)
    ';

    //TODO make way to query all invoices for event (all users)
    public static function query_invoices($userid, $eventid = NULL) {
        $inv_table = UserInvoices::TABLE_NAME;
        //if eventid is null then returns all user invoices
        if( empty($eventid) ) {
            $result = self::query(
              "SELECT * FROM $inv_table WHERE user_id = '$userid';"
            );
        }
        else {
            $result = self::query(
              "SELECT * FROM $inv_table WHERE user_id = '$userid' AND scheduled_event_id = '$eventid';"
            );
        }
        $invoices = array();
        foreach($result as $row) {
            $obj = UserInvoices::create_instance_from_row( $row );
            array_push($invoices, $obj);
        }
        return $invoices;
    }
}

?>
