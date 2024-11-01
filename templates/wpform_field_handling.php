<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly


add_action( 'wpforms_process_complete', 'wpf_dev_process_complete', 10, 4 );

function wpf_dev_process_complete($fields, $entry) 
{
	$ArraytoApi=[];
	$post_id = $entry['id'];

	$thirdparty = 'wpform';
	$activated_crm = get_option( 'WpLeadBuilderProActivatedPlugin' );
	$get_wpform_option = $activated_crm.'_wp_wpform_lite'.$post_id;
	$check_map_exist = get_option ( $get_wpform_option );

	if( !empty( $check_map_exist ))
	{

		$submit_array = [];
		$crm_array = $check_map_exist['fields'];
		$third_party_array = [];

		foreach($fields as $field_key => $field_value){
			$third_party_array[$field_value['name']] = $field_value['value'];
		}

		foreach($crm_array as $crm_key => $crm_values){
			if(array_key_exists($crm_key , $third_party_array)){
				$submit_array[$crm_values] = $third_party_array[$crm_key];
			}
		}

		$ArraytoApi['posted'] = $submit_array;
		$ArraytoApi['third_module'] = $check_map_exist['third_module'];
		$ArraytoApi['thirdparty_crm'] = $check_map_exist['thirdparty_crm'];
		$ArraytoApi['third_plugin'] = $check_map_exist['third_plugin'];
		$ArraytoApi['form_title'] = $check_map_exist['form_title'];
		$ArraytoApi['shortcode'] = $get_wpform_option;
		$ArraytoApi['duplicate_option'] = $check_map_exist['thirdparty_duplicate'];
		$capture_obj = new CapturingProcessClassPRO();
		$capture_obj->thirdparty_mapped_submission($ArraytoApi);

	}
}

