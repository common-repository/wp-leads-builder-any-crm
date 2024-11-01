<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class SmackLBAdminAjax {

	public static function smlb_ajax_events(){

		$ajax_actions = array(
			'selectplugpro' => false,
			'SaveCRMconfig' => false,
			'save_campaign_details' => 'false',
			'save_apikey' => 'false',
			'wp_usersync_assignedto' => 'false',
			'TFA_auth_save' => 'false',
			'mappingmodulepro' => 'false',
			'Sync_settings_PRO' => 'false',
			'saveSyncValue' => 'false',
			'send_mapping_configuration' => 'false',
			'createnew_form'=>'false',
			'get_thirdparty_fields' => 'false',
			'map_thirdparty_fields' => 'false',
			'save_thirdparty_form_title' => 'false',
			'send_mapped_config' => 'false',
			'delete_mapped_config' => 'false',
			'captcha_info' => 'false',
			'droptable_info'=>'false',
			'import_file'=>'false',
			'file_import'=>'false',
			'download_json'=>'false',
			'saveSFSettings' => 'false',
			'saveZohoSettings' => 'false',
			'save_usersync_RR_option' => 'false',
			'customfieldpro' => 'false',
			'smack_leads_builder_pro_change_menu_order' => 'false',
			'send_order_info' => 'false',
			'change_ecom_module_config' => 'false',
			'save_convert_lead' => 'false',
			'map_ecom_fields' => 'false',
			'map_sync_user_fields' => 'false',
			'adminAllActionsPRO' => 'false',
			'SaveSuiteconfig' => 'false',
			'zohoCRMRedirect' => 'false',
		);

		foreach($ajax_actions as $action => $value ){
			add_action('wp_ajax_'.$action, array(__CLASS__, $action));
		}
	}

	public static function selectplugpro()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once(SM_LB_PRO_DIR . "templates/plugin-select.php");
		die;
	}

	public static function SaveSuiteconfig(){
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		update_option('WpLeadBuilderProActivatedPlugin', 'wpsuitepro');
		echo esc_attr('success');
		die();
	}

	public static function createnew_form()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		if(sanitize_text_field($_REQUEST['Action']) == 'createshortcode')
		{
			require_once(SM_LB_PRO_DIR . "includes/class_lb_manage_shortcodes.php");
			$createshortcode = new ManageShortcodesActions();
			$value = $createshortcode->CreateShortcode(sanitize_text_field($_REQUEST['Module']));
			$value['onAction'] = 'onCreate';
		}elseif(sanitize_text_field($_REQUEST['Action']) == 'Editshortcode')
		{
			$value = array();
			$value['shortcode'] = sanitize_text_field($_REQUEST['shortcode']);
			$value['module'] = sanitize_text_field($_REQUEST['Module']);
			$value['crmtype'] =  sanitize_text_field($_REQUEST['plugin']);
			$value['onAction'] = 'onEditShortCode';
		} 
		else
		{
			require_once(SM_LB_PRO_DIR . "includes/class_lb_manage_shortcodes.php");
			$deleteshortcode = new ManageShortcodesActions();
			$deleteshortcode->DeleteShortcode(sanitize_text_field($_REQUEST['shortcode']));
			$value = array();
		}	
		// $shortcodevalues = json_encode($value);
		echo wp_json_encode($value);die;
	}
	public static function SaveCRMconfig( )
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR ."templates/saveCRMConfig.php" );
		die;
	}


	public static function adminAllActionsPRO()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR ."includes/Functions.php" );
		$adminObj = new AjaxActionsClassPRO();
		$adminObj->adminAllActionsPRO();
		die;
	}

	public static function save_campaign_details(){
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$save_camp_array = array();
		$save_camp_array['camp_name'] = sanitize_text_field($_REQUEST['camp_name']);
		$save_camp_array['utm_source'] = sanitize_text_field($_REQUEST['utm_source']);
		$save_camp_array['camp_medium'] = sanitize_text_field($_REQUEST['camp_medium']);
		$save_camp_array['utm_name'] = sanitize_text_field($_REQUEST['utm_name']);
		update_option('Campaign_details' , $save_camp_array);
		die;
	}


	public static function save_apikey(){
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$mc_api_key = sanitize_text_field($_REQUEST['mc_apikey']);
		update_option('mc_apikey' , $mc_api_key);
		require_once( SM_LB_PRO_DIR ."templates/getCampaignList.php" );
	}

	public static function captcha_info()
	{
		$final_captcha_array=[];
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$final_captcha_array['email'] = sanitize_text_field($_REQUEST['email'] );
		$final_captcha_array['emailcondition'] = sanitize_text_field($_REQUEST['emailcondition'] );
		$final_captcha_array['debugmode'] = sanitize_text_field($_REQUEST['debugmode'] );
		update_option("wp_captcha_settings", $final_captcha_array );
		die;
	}
	public static function droptable_info()
	{
		$droptable_info=[];
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$droptable_info['droptable']=sanitize_text_field($_REQUEST['droptable']);
		update_option("wp_droptable_settings",$droptable_info);
		die;
	}
	public static function import_file(){
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$thirdparty_values = sanitize_text_field($_REQUEST['value']);
		$thirdparty_val = str_replace("\\" , '' , $thirdparty_values);
		$third_party_value = json_decode($thirdparty_val);
		$value=$third_party_value->CRM_FORMS;
		$array_value=(array)$value;
		$array_val=(array)$array_value['fields'];
		$array_value['fields']=$array_val;
		update_option("wp_importfree_file",$array_value);
		die;

	}
	public static function file_import(){
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$option_value=get_option("wp_importfree_file");  
		$Active_plugin=get_option('WpLeadBuilderProActivatedPlugin');   
		$plugin = $option_value['third_plugin'];
		if($Active_plugin == 'joforce'){
			$active_plugins = get_option( "active_plugins" );
			if(in_array( "gravityforms/gravityforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_gravity'.$form_title;
				if($plugin == "gravityform"){
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "ninja-forms/ninja-forms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_ninja'.$form_title;
				if($plugin == "ninjaform"){
					update_option($option_name,$option_value);
				} 
			}
			if(in_array( "contact-form-7/wp-contact-form-7.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_contact'.$form_title;
				if($plugin == "contactform"){
					update_option($option_name,$option_value);
				}           
			}
			if(in_array( "wpforms-lite/wpforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_wpform_lite'.$form_title;
				if($plugin == "wpform"){
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "wpforms/wpforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_wpform_pro'.$form_title;
				if($plugin == "wpformpro"){	      		                
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "caldera-forms/caldera-core.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_caldera'.$form_title;
				if($plugin == "calderaform"){
					update_option($option_name,$option_value);
				}         
			}
		}
		else if($Active_plugin == 'wpsuitepro'){
			$active_plugins = get_option( "active_plugins" );

			if(in_array( "gravityforms/gravityforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_gravity'.$form_title;
				if($plugin == "gravityform"){
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "ninja-forms/ninja-forms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_ninja'.$form_title;
				if($plugin == "ninjaform"){
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "contact-form-7/wp-contact-form-7.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_contact'.$form_title;
				if($plugin == "contactform"){
					update_option($option_name,$option_value);
				}

			}
			if(in_array( "wpforms-lite/wpforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_wpform_lite'.$form_title;
				if($plugin == "wpform"){
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "wpforms/wpforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_wpform_pro'.$form_title;
				if($plugin == "wpformpro"){	
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "caldera-forms/caldera-core.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_caldera'.$form_title;
				if($plugin == "calderaform"){
					update_option($option_name,$option_value);
				}
			}                       
		}
		else if($Active_plugin == 'wptigerpro'){
			$active_plugins = get_option( "active_plugins" );

			if(in_array( "gravityforms/gravityforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_gravity'.$form_title;
				if($plugin == "gravityform"){
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "ninja-forms/ninja-forms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_ninja'.$form_title;
				if($plugin == "ninjaform"){
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "contact-form-7/wp-contact-form-7.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_contact'.$form_title;
				if($plugin == "contactform"){
					update_option($option_name,$option_value);
				}

			}
			if(in_array( "wpforms-lite/wpforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_wpform_lite'.$form_title;
				if($plugin == "wpform"){
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "wpforms/wpforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_wpform_pro'.$form_title;
				if($plugin == "wpformpro"){	
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "caldera-forms/caldera-core.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_caldera'.$form_title;
				if($plugin == "calderaform"){
					update_option($option_name,$option_value);
				}
			}                       
		}
		else{
			$active_plugins = get_option( "active_plugins" );

			// if(in_array( "gravityforms/gravityforms.php" , $active_plugins)) {
			//         $form_title=$option_value['form_title'];
			//         $option_name= $Active_plugin.'_wp_gravity'.$form_title;
			//         update_option($option_name,$option_value);
			// }
			// if(in_array( "ninja-forms/ninja-forms.php" , $active_plugins)) {
			//         $form_title=$option_value['form_title'];
			//         $option_name= $Active_plugin.'_wp_ninja'.$form_title;
			//         update_option($option_name,$option_value);
			// }
			if(in_array( "contact-form-7/wp-contact-form-7.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_contact'.$form_title;
				if($plugin == "contactform"){
					update_option($option_name,$option_value);
				}

			}
			if(in_array( "wpforms-lite/wpforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_wpform_lite'.$form_title;
				if($plugin == "wpform"){
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "wpforms/wpforms.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_wpform_pro'.$form_title;
				if($plugin == "wpformpro"){	
					update_option($option_name,$option_value);
				}
			}
			if(in_array( "caldera-forms/caldera-core.php" , $active_plugins)) {
				$form_title=$option_value['form_title'];
				$option_name= $Active_plugin.'_wp_caldera'.$form_title;
				if($plugin == "calderaform"){
					update_option($option_name,$option_value);
				}
			}                       
		}
	}

	public static function download_json(){
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		global $wpdb;
		$download_id=sanitize_text_field($_REQUEST['value']);
		$download_json=array();

		$value=get_option('WpLeadBuilderProActivatedPlugin');
		if($value == 'joforce')
		{
			$joforce_option_name=array();
			$joforce_name_list=$wpdb->get_results($wpdb->prepare("SELECT option_name from wp_options where option_name like '%$download_id%'"));
			foreach ($joforce_name_list as $value1) {
				$joforce_name=$value1->option_name;
				$opt_name=explode('_',$joforce_name);
				if($value == $opt_name[0]){
					$joforce_option_name[]=$joforce_name;
				}
			}
			foreach($joforce_option_name as $joforce_name) {
				$download_json['CRM_FORMS']	=get_option($joforce_name);
			}
			// $download_json['mappedfields_capture_settings']=get_option('smack_joforce_mappedfields_capture_settings');
			// $download_json['user_capture_settings']=get_option('smack_joforce_user_capture_settings');
			// $download_json['userfields_capture_settings']=get_option('smack_joforce_userfields_capture_settings');
			// $download_json['UserjoforceArrayModuleMapping']=get_option('UserjoforceArrayModuleMapping');
			// $download_json['UserjoforceLeadsModuleMapping']=get_option('UserjoforceLeadsModuleMapping');
		}
		else{
			$wpsuitepro_name_list=$wpdb->get_results($wpdb->prepare("SELECT option_name from wp_options where option_name like '%$download_id%'"));
			foreach ($wpsuitepro_name_list as $value1) {
				$wpsuitepro_name=$value1->option_name;
				$opt_name=explode('_',$wpsuitepro_name);
				if($value == $opt_name[0]){
					$wpsuitepro_option_name[]=$wpsuitepro_name;
				}
			}
			foreach($wpsuitepro_option_name as $wpsuitepro_name){
				$download_json['CRM_FORMS']=get_option($wpsuitepro_name);
			}
			// $download_json['mappedfields_capture_settings']=get_option('smack_joforce_mappedfields_capture_settings');
			// $download_json['wpsuitepro_user_capture_settings']=get_option('smack_wpsuitepro_user_capture_settings');
			// $download_json['wpsuitepro_userfields_capture_settings']=get_option('smack_wpsuitepro_userfields_capture_settings');
			// $download_json['UserwpsuiteproArrayModuleMapping']=get_option('UserwpsuiteproArrayModuleMapping');
			// $download_json['UserwpsuiteproLeadsModuleMapping']=get_option('UserwpsuiteproLeadsModuleMapping');
		}
		// $download_json['Captcha_Settings']=get_option('wp_captcha_settings');

		echo wp_json_encode($download_json);
		wp_die();

	}
	public static function wp_usersync_assignedto()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR ."templates/wp_assignedtouser.php" );
		die;
	}

	public static function TFA_auth_save()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$TFA_Authtoken_value = sanitize_text_field( $_REQUEST['authtoken']);
		$ActivePlugin = get_option('WpLeadBuilderProActivatedPlugin');
		if( $ActivePlugin == 'wpzohopro')
			$smack_TFA_label = 'TFA_zoho_authtoken';
		else
			$smack_TFA_label = 'TFA_zoho_plus_authtoken';

		update_option($smack_TFA_label , $TFA_Authtoken_value );
		echo esc_attr($TFA_Authtoken_value);
		die;
	}

	public static function mappingmodulepro()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$map_module = sanitize_text_field($_REQUEST['postdata']);
		update_option( 'WpMappingModule' , $map_module );
		die;
	}

	public static function Sync_settings_PRO( )
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR .'templates/Sync-settings.php' );
		die;
	}

	public static function saveSyncValue()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR .'templates/save-sync-value.php' );
		die;
	}

	public static function send_mapping_configuration()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR .'templates/thirdparty_mapping.php' );
		$module = sanitize_text_field( $_REQUEST['thirdparty_module'] );
		$thirdparty_form = sanitize_text_field( $_REQUEST['thirdparty_plugin'] );
		$mapping_ui_fields = new thirdparty_mapping();
		$mapping_ui_fields->mapping_form_fields($module , $thirdparty_form );
	}

	public static function get_thirdparty_fields()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR .'templates/thirdparty_mapping.php' );
		$mapping_ui_fields = new thirdparty_mapping();
		$mapping_ui_fields->get_thirdparty_form_fields();
	}

	public static function map_thirdparty_fields()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR .'templates/thirdparty_mapping.php' );
		$mapping_ui_fields = new thirdparty_mapping();
		$mapping_ui_fields->map_thirdparty_form_fields();
	}

	public static function save_thirdparty_form_title()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$thirdparty_title_key = sanitize_text_field($_REQUEST['tp_title_key']);
		$thirdparty_title_value = sanitize_text_field( $_REQUEST['tp_title_val'] );
		update_option( $thirdparty_title_key , $thirdparty_title_value );
		die;
	}

	public static function send_mapped_config()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR .'templates/thirdparty_mapping.php' );
		$mapping_ui_fields = new thirdparty_mapping();
		$mapping_ui_fields->show_mapped_config();
	}

	public static function delete_mapped_config()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR .'templates/thirdparty_mapping.php' );
		$mapping_ui_fields = new thirdparty_mapping();
		$mapping_ui_fields->delete_mapped_configuration();
	}

	public static function saveSFSettings() {
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$key = sanitize_text_field($_POST['key']);
		$value = sanitize_text_field($_POST['value']);
		$exist_config = get_option("wp_wpsalesforcepro_settings");
		$config = $current_config = array();
		switch ($key) {
		case 'key':
			$current_config['key'] = $value;
			break;
		case 'secret':
			$current_config['secret'] = $value;
			break;
		case 'callback':
			$current_config['callback'] = $value;
			break;
		}
		if(!empty($exist_config))
			$config = array_merge($exist_config, $current_config);
		else
			$config = $current_config;
		update_option('wp_wpsalesforcepro_settings', $config);
		die;
	}

	public static function saveZohoSettings() {
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$key = sanitize_text_field($_POST['key']);
		$value = sanitize_text_field($_POST['value']);
		$active_plugin = get_option("WpLeadBuilderProActivatedPlugin");
		$exist_config = get_option("wp_{$active_plugin}_settings");
		$config = $current_config = array();
		switch ($key) {
		case 'key':
			$current_config['key'] = $value;
			break;
		case 'secret':
			$current_config['secret'] = $value;
			break;
		case 'callback':
			$current_config['callback'] = $value;
			break;
		case 'domain':
			if(empty($value)){
				$value = ".com";
			}
			if($value == '.au'){
				$value = ".com.au";
			}
			$current_config['domain'] = $value;
			break;
		}

		if(!isset($exist_config['domain'])){
			$current_config['domain'] = ".com";
		}

		if(!empty($exist_config))
			$config = array_merge($exist_config, $current_config);
		else
			$config = $current_config;

		update_option("wp_{$active_plugin}_settings", $config);
		die;
	}

	public static function zohoCRMRedirect(){
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$active_plugin = get_option("WpLeadBuilderProActivatedPlugin");
		$config = get_option("wp_{$active_plugin}_settings");
		$domain = isset($config['domain']) ? $config['domain'] : '.com';
		$con_key = isset($config['key']) ? $config['key'] : '';
		$auth_url =  "https://accounts.zoho". $domain ."/oauth/v2/auth?scope=ZohoCRM.users.ALL,ZohoCRM.modules.ALL,ZohoCRM.settings.ALL,ZohoCRM.org.ALL&client_id=" . $con_key . "&response_type=code&access_type=offline&redirect_uri=" . $config['callback'];
		
		echo wp_json_encode($auth_url);
		wp_die();
	}


	public static function save_usersync_RR_option()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$usersync_RR_value = sanitize_text_field( $_REQUEST['user_rr_val'] );
		update_option('usersync_rr_value' , $usersync_RR_value );
		die;
	}

	public static function customfieldpro()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$custom_plugin = sanitize_text_field($_REQUEST['postdata']);
		$active_plugins = get_option( "active_plugins" );
		switch( $custom_plugin )
		{
		case 'acf':
			if( in_array( "advanced-custom-fields/acf.php" , $active_plugins ) ) {
				update_option('custom_plugin',$custom_plugin);
				$activated = "yes" ;
			}
			else {
				$activated = "no" ;
			}
			break;

		case 'acfpro':
			if( in_array( "advanced-custom-fields-pro/acf.php" , $active_plugins ) ) {
				update_option('custom_plugin',$custom_plugin);
				$activated = "yes" ;
			}
			else {
				$activated = "no" ;
			}
			break;

		case 'wp-members':
			if( in_array( "wp-members/wp-members.php" , $active_plugins) ) {
				update_option('custom_plugin',$custom_plugin);
				$activated = "yes" ;
			}
			else {
				$activated = "no" ;
			}
			break;

		case 'member-press':
			if( in_array( "memberpress/memberpress.php" , $active_plugins) ) {
				update_option('custom_plugin',$custom_plugin);
				$activated = "yes" ;
			}
			else {
				$activated = "no" ;
			}
			break;

		case 'ultimate-member':
			if( in_array( "ultimate-member/ultimate-member.php" , $active_plugins ) ) {
				update_option('custom_plugin',$custom_plugin);
				$activated = "yes" ;
			}
			else {
				$activated = "no" ;
			}
			break;

		case 'none':
			update_option('custom_plugin',$custom_plugin);
			$activated = "yes" ;
			break;
		}
		echo esc_attr($activated);die;
	}

	public static function smack_leads_builder_pro_change_menu_order( $menu_order ) {
		return array(
			'index.php',
			'edit.php',
			'edit.php?post_type=page',
			'upload.php',
			'wp-leads-builder-any-crm/index.php',
		);
	}

	public static function send_order_info($order_id) {

	}

	public static function change_ecom_module_config()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR .'templates/ecom_config.php');
		$ecom_obj = new ecom_configuration();
		$ecom_obj->change_module_config();
	}

	public static function save_convert_lead()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$convert_val = sanitize_text_field( $_REQUEST['convert_lead']);
		update_option( 'ecom_wc_convert_lead' , $convert_val );
	}

	public static function map_ecom_fields()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once( SM_LB_PRO_DIR .'templates/ecom_config.php');
		$ecom_obj = new ecom_configuration();
		$ecom_obj->map_ecom_module_configuration();
	}

	public static function map_sync_user_fields()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		require_once(SM_LB_PRO_DIR . "includes/lb-syncuser.php");
		$request = $_REQUEST;
		foreach($request as $requestKey => $requestVal ){
			if(is_array($requestVal)){
				$map_fields = array_map( 'sanitize_text_field', $requestVal );
			} 
			else{
				if($requestKey == 'mapvariable'){
					$map_variable = sanitize_text_field($requestVal);
				}
			}
		}
		if($map_variable == 'Leads_module_field' ) {
			$module = 'Leads';
		} else {
			$module = 'Contacts';
		}
		$activated_plugin = get_option( "WpLeadBuilderProActivatedPlugin" );
		update_option("smack_{$activated_plugin}_mappedfields_capture_settings",$map_fields);
		$data = new SyncUserActions();
		$module = $data->ModuleMapping('',$map_fields,'update');
		update_option( "User{$activated_plugin}{$module}ModuleMapping" , $map_fields );
		header("Refresh:0");
		echo wp_json_encode($map_fields);
		die;	
	}
}	
