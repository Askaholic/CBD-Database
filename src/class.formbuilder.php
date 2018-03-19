<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * FormBuilder
 * Helper functions for creating form elements more easily
 */
class FormBuilder
{

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
