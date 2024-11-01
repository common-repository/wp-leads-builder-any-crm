<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
require_once('SmackContactFormGenerator.php');
add_action('wpcf7_before_send_mail','contact_forms_example');
function replace_key_function($all_fields, $key1, $key2)
{
	$keys = array_keys($all_fields);
	$index = array_search($key1, $keys);
	if ($index !== false) {
		$keys[$index] = $key2;
		$all_fields = array_combine($keys, $all_fields);
	}
	return $all_fields;
}

function getTextBetweenBrackets($post_content) {

	$data_type_array = array( 'text' , 'email' , 'date' , 'checkbox' , 'select' , 'url' , 'number' , 'textarea' , 'radio' , 'quiz' , 'file', 'acceptance' , 'hidden' , 'tel' , 'dynamichidden');

	$contact_labels = array();
	foreach( $data_type_array as $dt_key => $dt_val )
	{
		$patternn = "(\[$dt_val(\s|\*\s)(.*)\])";
		preg_match_all($patternn, $post_content, $matches);
		if( !empty( $matches[1] ))
		{
			$contact_labels[] = $matches[0];
		}

		$i =0;
		$merge_array = array();
		foreach( $contact_labels as $cf7key => $cf7value )
		{
			foreach( $cf7value as $cf_get_key => $cf_get_fields )
			{
				$merge_array[] = $cf_get_fields;
			}
		}
	}
	return $merge_array;
}


function contact_forms_example()
{
	global $wpdb;
	$ArraytoApi=[];
	$post_id = intval($_POST['_wpcf7']);
	$thirdparty = 'contactform';
	$contact_form_field_type=[];
	$activated_crm = get_option( 'WpLeadBuilderProActivatedPlugin' );
	$get_contact_option = $activated_crm.'_wp_contact'.$post_id;
	$noe = '';
	$check_map_exist = get_option( $get_contact_option );
	if( !empty( $check_map_exist ))
	{
		$all_fields = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$submission = WPCF7_Submission::get_instance();
		$attachments = $submission->uploaded_files();
		foreach($all_fields as $key=>$value)    {
			if(preg_match('/^_wp/',$key))
				unset($all_fields[$key]);
		}
		//	g-recaptcha-response

		foreach($all_fields as $cfkey => $cfvalue)
		{
			if( $cfkey == 'g-recaptcha-response' )
			{
				if( empty( $cfvalue ) )
				{
					die;	
				}			
			}
		}

		$mapped_array = $check_map_exist['fields'];
		$mapped_array_key_labels = array_keys( $mapped_array );

		$get_json_array = $wpdb->get_results( $wpdb->prepare( "select ID,post_content from $wpdb->posts where ID=%d" , $post_id ) );
		$contact_post_content = $get_json_array[0]->post_content;
		$fields = getTextBetweenBrackets( $contact_post_content );
		$i = 0;
		foreach( $fields as $cfkey => $cfval )
		{
			if( preg_match( '/\s/' , $cfval ) )
			{
				$final_arr = explode( ' ' , $cfval );
				$contact_form_field_type[$i] = preg_replace('/^\[/', '', $final_arr[0]);
				$contact_form_labels[$i] = rtrim( $final_arr[1] , ']' );
				$i++;
			}
		}


		//get mapped label keys from gravity array
		foreach( $contact_form_labels as $cf_key => $cf_val)
		{
			foreach( $mapped_array_key_labels as $labels )
			{
				if( $labels == $cf_val && $labels != 'gclid_value' )
				{
					$field_name = $mapped_array[$labels];
					if (isset($all_fields[$labels])) {
					$user_value = $all_fields[$labels];
					}
					$data_array[$field_name] = $user_value;
				}
			}
		}

		$activatedPlugin = $check_map_exist['thirdparty_crm'];
		if(!empty($data_array)) {
				$i=0;
			foreach ( $data_array as $key => $value ) {
				if ( $key == '' ) {
					$noe = $key;
				}
				if ( is_array( $data_array[ $key ] ) ) {
					switch ( $activatedPlugin ) {
					case 'wptigerpro':
						if($contact_form_field_type[$i] ==='checkbox'){
							$data_array[ $key ] = filter_var('true', FILTER_VALIDATE_BOOLEAN);
						}	
						break;

					case 'wpsugarpro':
					case 'wpsuitepro':
						if($contact_form_field_type[$i] ==='checkbox'){
						$data_array[ $key ] = 'on';
						}
						if(isset($data_array[ $key ])){
							if($contact_form_field_type[$i] ==='select'){
								foreach($data_array[ $key ] as $value =>$val){
									$data_array[ $key ]=$val;
								}
							}
						}
						break;

					case 'wpzohopro':
						if($contact_form_field_type[$i] ==='checkbox'){
							$data_array[ $key ] = filter_var('true', FILTER_VALIDATE_BOOLEAN);
						}	
						break;

					case 'wpzohopluspro':
						if($contact_form_field_type[$i] ==='checkbox'){
							$data_array[ $key ] = filter_var('true', FILTER_VALIDATE_BOOLEAN);
						}	
						break;
						
					case 'wpsalesforcepro':
						if($contact_form_field_type[$i] ==='checkbox'){
							$data_array[ $key ] = filter_var('true', FILTER_VALIDATE_BOOLEAN);
						}
                        if($contact_form_field_type[$i] ==='select'){
							if(is_array($data_array[ $key ])){
                            $data_array[ $key ] = implode(";", $data_array[ $key ]);                            
						}} 
						break;

					case 'freshsales':
						$data_array[ $key ] = '1';
						break;
					}
				}
				$i++;
			}
			unset( $data_array[ $noe ] );
		}
		// Change drop down value to id for Fresh sales CRM
		if( $activatedPlugin == 'freshsales' )
		{
			$fs_module = strtolower( $check_map_exist['third_module'] );
			$fs_module = rtrim( $fs_module , 's' );
			$freshsales_option = get_option( "smack_{$activatedPlugin}_{$fs_module}_fields-tmp" );
			foreach( $freshsales_option['fields'] as $fs_key => $fs_option )
			{
				foreach( $data_array as $field_name => $posted_val ) {
					if( $fs_option['type']['name'] == 'picklist' && $fs_option['fieldname'] == $field_name )
					{
						foreach( $fs_option['type']['picklistValues'] as $pick_key => $pick_val )
						{
							if( $pick_val['label'] == $posted_val )
							{
								$data_array[$field_name] = $pick_val['id'];
							}
						}

					}
					if( $fs_option['type']['name'] == 'boolean' && $fs_option['fieldname'] == $field_name && $posted_val == "" )
					{
						$data_array[$field_name] = '0';
					}
				}
			}
		}
		//Attachment field
		if($attachments){
			$data_array['attachments'] = $attachments;
		}

		$ArraytoApi['posted'] = $data_array;
		$ArraytoApi['third_module'] = $check_map_exist['third_module'];
		$ArraytoApi['thirdparty_crm'] = $check_map_exist['thirdparty_crm'];
		$ArraytoApi['third_plugin'] = $check_map_exist['third_plugin'];
		$ArraytoApi['form_title'] = $check_map_exist['form_title'];
		$ArraytoApi['shortcode'] = $get_contact_option;
		$ArraytoApi['duplicate_option'] = $check_map_exist['thirdparty_duplicate'];
		$capture_obj = new CapturingProcessClassPRO();
		$capture_obj->thirdparty_mapped_submission($ArraytoApi);

	}
}