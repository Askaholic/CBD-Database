<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * FormBuilder
 * Helper functions for creating form elements more easily
 */
class FormBuilder
{
    /* Event template form functions */
    //TODO set this to match in create event
    static function eventDescription($desc) {
        $html = '<p> '.$desc . '</p><br>';
        echo $html;
        return $html;
    }

    static function userInfoForm($user = NULL) {
        $first = '';
        $last = '';

        if( $user ) {
            $first = $user->first_name;
            $last = $user->last_name;
        }
        $name = self::input('text', 'first_name', 'First Name', "required value='$first'", false )
                . self::input('text', 'last_name', 'Last Name', "required value='$last'" , false );
        $html .= $name;
        $html .= self::input('checkbox', 'checked_waiver', 'I have read, understand, and accept the terms of the<a href="http://contraborealis.org/wp-content/uploads/DCN/DCN-Liability-Release-Form.pdf" >Waiver and Release of Liability</a>', 'required', false);
        $html .= '<p>Not you? <a href="logout"> logout</a></p>';
        echo $html;
        return $html;
    }
    static function childInfoForm(){
        $people = self::input('number', 'children', 'Children (Ages 6-12)', ' min="0" max="200" value="0" ',true)
                . self::input('number', 'young_adults', 'Young Adults (Ages 13-25)', ' min="0" max="200" value="0" ',true)
                . self::input('number', 'adults', 'Adults (Ages 25+)', ' min="0" max="200" value="0" ',true);
    }

    private static function _input( $type, $id, $extraattrs = '') {
        return '<input type="' . $type . '" id="' . $id . '" name="' . $id . '" ' .  $extraattrs . ' />';
    }
    /* Create an input field with a label */
    static function input( $type, $id, $display_text, $extraattrs = '', $echo = true ) {
        $label = '<label for="' . $id . '">' . $display_text . '</label><br/>';
        $input = self::_input( $type, $id, $extraattrs);
        $html = $label . $input . '<br/>';

        if ($echo) {
            echo $html;
        }
        return $html;
    }

    /* Convinience function for use with the form function */
    static function input_f( $type, $name, $display_text, $default_value = '') {
        return [ $display_text, self::_input( $type, $name, $display_text, $default_value )];
    }

    static function options_form( $method, $action) {
        $form = '<form method="' . $method . '"';

        if ( $action != '' ) {
            $form .= 'action="' . $action . '"';
        }

        $form .= '><table class="form-table">';

        $elements = array_slice(func_get_args(), 2);

        foreach ($elements as $content) {
            $form .= '<tr><th>' . $content[0] . '</th>';
            $form .= '<td>' . $content[1] . '</td></tr>';
        }

        $form .= '</table></form>';

        echo $form;
    }
}


?>
