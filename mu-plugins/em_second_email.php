<?php
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 5/5/2016
 * Time: 10:40 PM
 */

//This set of snippets adds a secondary email field so that the user must provide matching values in each field, to help prevent user typos.
function my_emp_email_validation_output( $content, $EM_Form, $field ){
	if( !is_user_logged_in() && $field['type'] == 'user_email' ){
		$label = 'Confirm '. $field['label']; //change this to alter label of second field
		$content .= str_replace($field['label'], $label, str_replace($field['fieldid'],$field['fieldid'].'_2', $content));
	}
	return $content;
}
add_filter('emp_forms_output_field', 'my_emp_email_validation_output', 10, 3);

function my_emp_email_validation( $result, $field, $value, $EM_Form ){
	if( !is_user_logged_in() && $field['type'] == 'user_email' && $result ){
		if( $value != $_REQUEST[$field['fieldid'].'_2'] ){
			//modify this next line for a different error message
			$EM_Form->add_error( 'Emails do not match, please confirm your email below.' );
		}
	}
	return $result;
}
add_filter('emp_form_validate_field', 'my_emp_email_validation', 10, 4);