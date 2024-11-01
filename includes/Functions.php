<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

/*
Cases : 
1) CreateNewFieldShortcode		Will create new field shortcode
2) FetchCrmFields			Will Fetch crm fields from the the crm
3) FieldSwitch				Enable/Disable single field
4) DuplicateSwitch			Change Duplicate handling settings 
5) MoveFields				Change the order of the fields
6) MandatorySwitch			Make Mandatory or Remove Mandatory
7) SaveDisplayLabel			Save Display Label
8) SwitchMultipleFields			Enable/Disable multiple fields
9) SwitchWidget				Enable/Disable widget  form
10) SaveAssignedTo			Save Assignee of the form leads 
11) CaptureAllWpUsers			Capture All wp users
 */

class OverallFunctionsPRO {

	public function CheckFetchedDetails()
	{
		$HelperObj = new WPCapture_includes_helper_PRO();
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$shortcodeObj = new CaptureData();
		$leadsynced = $shortcodeObj->selectFieldManager( $activatedplugin , 'Leads' );
		$users = get_option('crm_users');
		$usersynced = false;
		if( is_array($users[$activatedplugin]) && count( $users[$activatedplugin] ) > 0 )
		{
			$usersynced = true;
		}
		$content = "";
		$flag = true;

		if( !$leadsynced || !$usersynced )
		{
			$content = __( "Please configure your CRM in the CRM Configuration" , "wp-leads-builder-any-crm-pro"  );
			$flag = false;
		}
		$return_array = array( 'content' => "$content" , 'status' => $flag );
		return $return_array;
	}

	public function CreateNewFieldShortcode( $crmtype , $module ){
		$moduleslug = rtrim( strtolower($module) , "s");
		$tmp_option = "smack_{$crmtype}_{$moduleslug}_fields-tmp";
		if(!function_exists("generateRandomStringActivate"))
		{
			function generateRandomString($length = 10) {
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, strlen($characters) - 1)];
				}
				return $randomString;
			}
		}
		$list_of_shorcodes = Array();
		$shortcode_pre_flag = "No";
		$options = "smack_fields_shortcodes";
		$config_shortcodes = get_option($options);
		if(is_array($config_shortcodes))
		{
			foreach($config_shortcodes as $shortcode => $values)
			{
				$list_of_shorcodes[] = $shortcode;
			}
		}

		for($notpresent = "no" ; $notpresent == "no"; )
		{
			$random_string = generateRandomString(5);
			if(in_array($random_string, $list_of_shorcodes))
			{
				$shortcode_pre_flag = 'Yes';
			}
			if($shortcode_pre_flag != 'yes')
			{
				$notpresent = 'yes';
			}
		}
		$options = $tmp_option;
		return $random_string;
	}

	public static function doFieldAjaxAction()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$crmtype = isset($_REQUEST['crmtype']) ? sanitize_text_field($_REQUEST['crmtype']) : "";
		$module = isset($_REQUEST['module']) ? sanitize_text_field($_REQUEST['module']) : "";
		$options = sanitize_text_field($_REQUEST['option']);
		$onAction = sanitize_text_field($_REQUEST['onAction']);
		$HelperObj = new WPCapture_includes_helper_PRO();
		$label_array=[];
		$order_array=[];
		$moduleslug = $HelperObj->ModuleSlug;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$content = '';
		$allowed_html = ['div' => ['class' => true, 'id' => true, 'style' => true, ], 
			'a' => ['id' => true, 'href' => true, 'title' => true, 'target' => true, 'class' => true, 'style' => true, 'onclick' => true,], 
			'strong' => [], 
			'i' => ['id' => true, 'onclick' => true, 'style' => true, 'class' => true, 'aria-hidden' => true,'title' => true ], 
			'p' => ['style' => true, 'name' => true, 'id' => true, ], 
			'img' => ['id' => true, 'style' => true, 'class' => true, 'align' => true, 'src' => true, 'width' => true, 'height' => true, 'border' => true, ], 
			'table' => ['id' => true, 'class' => true, 'style' => true, 'height' => true, 'cellspacing' => true, 'cellpadding' => true, 'border' => true, 'width' => true, 'align' => true, 'background' => true, 'frame' => true, 'rules' => true, ], 
			'tbody' => [], 
			'br' => ['bogus' => true, ], 
			'tr' => ['id' => true, 'class' => true, 'style' => true, ], 
			'th' => ['id' => true, 'class' => true, 'style' => true, ], 
			'hr' => ['id' => true, 'class' => true, 'style' => true,], 
			'h3' => ['style' => true, ], 
			'td' => ['style' => true, 'id' => true, 'align' => true, 'width' => true, 'valign' => true, 'class' => true, 'colspan' => true, ], 
			'span' => ['style' => true, 'class' => true, ], 
			'h1' => ['style' => true, ], 
			'thead' => [], 
			'tfoot' => ['id' => true, 'style' => true, ], 
			'figcaption' => ['id' => true, 'style' => true, ], 
			'h4' => ['id' => true, 'align' => true, 'style' => true, ],
			'h2' => ['id' => true, 'align' => true, 'style' => true, 'class' => true],
			'script' => [],
			'select' => ['id' => true, 'name' => true, 'class' => true, 'data-size' =>true, 'data-live-search' =>true, 'onchange' => true],
			'option' => ['value' => true, 'selected' => true],
			'label' =>['id' => true, 'class' =>true],
			'input' => ['type' => true, 'value' => true, 'id' => true, 'name' => true, 'class' => true, 'onclick' => true],
			'form' => ['method' => true, 'name' => true, 'id' => true, 'action' => true]];
		if($crmtype == 'joforce'){
			$FunctionsObj = new joforceFunctions();
		}else{
			$FunctionsObj = new mainCrmHelper();
		}

		$tmp_option = "smack_{$activatedplugin}_{$moduleslug}_fields-tmp";
		if($onAction == 'onEditShortCode');
		{
			$original_options = "smack_{$activatedplugin}_fields_shortcodes";
			$original_config_fields = get_option($original_options);
		}
		if($onAction == 'onCreate')
		{
			$config_fields = get_option($options);
		}
		else
		{
			$config_fields = get_option($options);
		}
		$FieldCount = 0;
		if(isset($config_fields['fields']))
		{
			$FieldCount = count($config_fields['fields']);
		}
		$error=[];
		if(isset($config_fields)){
			$error[0] = 'no fields';
		}
		switch(sanitize_text_field($_REQUEST['doaction']))
		{
		case "GetAssignedToUser":
			if($crmtype == 'joforce'){
				$Functions = new joforceFunctions();
			}else{
				$Functions = new mainCrmHelper();
			}
			echo wp_kses($Functions->getUsersListHtml(),$allowed_html);
			break;
		case "CheckformExits":
			include(SM_LB_PRO_DIR.'includes/class_lb_manage_shortcodes.php');
			$fields = new ManageShortcodesActions();
			$request =$_REQUEST;
			foreach($request as $requestKey => $requestVal){
				if($requestKey == 'chkarray'){
					$requestVal = str_replace("\\", "", $requestVal);
					$chkarray = json_decode($requestVal, true);
					$chk_array = [];
					if (is_array($chkarray))
					{
						$i = 0;
						foreach ($chkarray as $value)
						{
							$chk_array[$i] = intval($value);
							$i++;
						}
					}
				}
				else if($requestKey == 'labelarray'){
					$requestVal = str_replace("\\", "", $requestVal);
					$labelarray = json_decode($requestVal, true);
					if (is_array($labelarray))
					{
						$i = 0;
						foreach ($labelarray as $value)
						{
							$label_array[$i] = sanitize_text_field($value);
							$i++;
						}
					}
				}
				else if($requestKey == 'orderarray'){
					$requestVal = str_replace("\\", "", $requestVal);
					$orderarray = json_decode($requestVal, true);
					if (is_array($orderarray))
					{
						$i = 0;
						foreach ($orderarray as $value)
						{
							$order_array[$i] = intval($value);
							$i++;
						}
					}
				}
				else if($requestKey == 'shortcode'){
					$shortcode = sanitize_text_field($requestVal);
				}
				else if($requestKey == 'crmtype'){
					$crmtype = sanitize_text_field($requestVal);
				}
				else if($requestKey == 'module'){
					$module = sanitize_text_field($requestVal);
				}
				else if($requestKey == 'bulkaction'){
					$bulkaction = sanitize_text_field($requestVal);
				}
			}
			$all_fields = $fields->ManageFields($shortcode, $crmtype, $module, $bulkaction, $chk_array, $label_array, $order_array);
			$moduleslug = rtrim( strtolower($module) , "s");
			$config_fields = get_option( "smack_{$crmtype}_{$moduleslug}_fields-tmp" );
			if( !isset($config_fields['fields'][0]) )
				die( "Not synced" );
			else
				die( "Synced" );
			break;
		case "GetTemporaryFields":
			$moduleslug = rtrim( strtolower($module) , "s");
			$config_fields = get_option( "smack_{$crmtype}_{$moduleslug}_fields-tmp" );
			if($options != 'getSelectedModuleFields')
			{
				include(SM_LB_PRO_DIR.'templates/crm-fields-form.php');
			}
			break;
		case "FetchCrmFields":
			$moduleslug = rtrim( strtolower($module) , "s");
			if($module == 'Leads'){
				$config_fields = $FunctionsObj->getCrmFields( $module );
			}
			$seq = 1;
			$field_details = $current_fields = $existing_fields = array();
			foreach($config_fields['fields'] as $fkey => $fval) {
				$field_details['name'] = $fval['name'];
				$field_details['label'] = $fval['label'];
				$field_details['type'] = isset($fval['type']['name']) ? $fval['type']['name'] : "";
				$field_details['field_values'] = null;
				if(! empty( $fval['type']['picklistValues'] ) ) {
					$field_details['field_values'] = serialize($fval['type']['picklistValues']);
				}
				$field_details['module'] = $module;
				if( isset($fval['mandatory']) && $fval['mandatory'] == 2 )
					$field_details['mandatory'] = 1;
				else
					$field_details['mandatory'] = 0;
				$field_details['crmtype'] = $crmtype;
				$field_details['sequence'] = $seq;
				$field_details['base_model'] = null;
				if(isset($fval['base_model']))
					$field_details['base_model'] = $fval['base_model'];
				$seq++;

				if($field_details['label']=='Date of Birth')
				{
					$field_details['type']='date';
				}
				$DataObj = new CaptureData();
				$DataObj->fieldManager( $field_details , $module );
				$DataObj->updateShortcodeFields( $field_details , $module );
				$current_fields[] = $field_details['name'];
			}
			if($options != 'getSelectedModuleFields')
			{
				include(SM_LB_PRO_DIR.'templates/display-log.php');
			}
			global $wpdb;
			$get_existing_fields = $wpdb->get_results( $wpdb->prepare("select field_name from wp_smackleadbulider_field_manager where module_type =%s and crm_type =%s", $module, $crmtype) );
			foreach($get_existing_fields as $ex_key => $ex_val){
				$existing_fields[] = $ex_val->field_name;
			}
			if(!empty($existing_fields))
			{
				$check_deleted_fields = array();
				$check_deleted_fields = array_diff($existing_fields , $current_fields);
				if(!empty($check_deleted_fields))
				{
					//Delete fields from table
					$DataObj = new CaptureData();
					$DataObj->DeleteFields( $crmtype , $module , $check_deleted_fields );
				}
			}
			//Update Current Fields
			$options = "smack_{$crmtype}_{$moduleslug}_fields-tmp";
			update_option($options, $config_fields);
			$options = "smack_fields_shortcodes";
			$edit_config_fields = get_option($options);
			$edit_config_fields[sanitize_text_field($_REQUEST['shortcode'])] = $config_fields;
			update_option($options, $edit_config_fields);
			break;
		case "FetchAssignedUsers":

			$HelperObj = new WPCapture_includes_helper_PRO();
			$module = $HelperObj->Module;
			$moduleslug = $HelperObj->ModuleSlug;
			$activatedplugin = $HelperObj->ActivatedPlugin;
			// $activatedpluginlabel = $HelperObj->ActivatedPluginLabel;
			if($activatedplugin == 'joforce'){
				$FunctionsObj = new joforceFunctions();
			}else{
				$FunctionsObj = new mainCrmHelper();
			}

			$crmusers = get_option( 'crm_users' );
			$users = $FunctionsObj->getUsersList();
			$crmusers[$activatedplugin] = $users;
			update_option('crm_users', $crmusers);
			$content .='<h5>Assigned Users:</h5>';
			$firstname = '';
			foreach($users['first_name'] as $assignusers)
			{
				$firstname .= $assignusers."<br>";
			}
			echo wp_kses($content,$allowed_html);
			echo esc_attr($firstname); die;
			break;
		default:
			break;
		}
	}

	public function update_formtitle( $shortcode , $tp_title , $tp_formtype ) 
	{
		global $wpdb;
		switch( $tp_formtype )	
		{
		case 'contactform':
			$get_checkid = $wpdb->get_results("select thirdpartyid from wp_smackformrelation where  shortcode='{$shortcode}' and thirdparty='contactform'");
			if(isset($get_checkid[0])) {
				$checkid = $get_checkid[0]->thirdpartyid;
			} else {
				$checkid = "";
			}
			if( !empty( $checkid ))
			{	
				$wpdb->update( $wpdb->posts , array('post_title' => $tp_title ) , array( 'ID' => $checkid ) );	
			}

			break;
		case 'wpform':
			$get_checkid = $wpdb->get_results("select thirdpartyid from wp_smackformrelation where shortcode='{$shortcode}' and thirdparty='wpform'");
			if(isset($get_checkid[0]))  {
				$checkid = $get_checkid[0]->thirdpartyid;
			} else {
				$checkid = "";
			}
			if( !empty( $checkid ))
			{
				$wpdb->update( $wpdb->posts , array('post-title' =>$tp_title ) , array( 'ID' => $checkid ) );
			}
			break;

		case 'wpformpro':
			$get_checkid = $wpdb->get_results("select thirdpartyid from wp_smackformrelation where shortcode='{$shortcode}' and thirdparty='wpformpro'");
			if(isset($get_checkid[0]))  {
				$checkid = $get_checkid[0]->thirdpartyid;
			} else {
				$checkid = "";
			}
			if( !empty( $checkid ))
			{
				$wpdb->update( $wpdb->posts , array('post-title' =>$tp_title ) , array( 'ID' => $checkid ) );
			}
			break;
		}
		return;
	}

	public function doNoFieldAjaxAction()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$shortcodedata =[];
		$HelperObj = new WPCapture_includes_helper_PRO();
		$module = $HelperObj->Module;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$shortcodeObj = new CaptureData();
		$action =sanitize_text_field($_REQUEST['doaction']);
		switch($action)
		{
		case "SaveFormSettings":
			$shortcode_name = sanitize_text_field($_REQUEST['shortcode']);
			$thirdparty_title = sanitize_text_field( $_REQUEST['thirdparty_title'] );
			$thirdparty_form_type = sanitize_text_field( $_REQUEST['thirdparty_form_type'] );
			if($thirdparty_form_type != 'none'){
				update_option( $shortcode_name , $thirdparty_title);
				update_option( 'Thirdparty_'.$shortcode_name , $thirdparty_form_type);
			}
			if( $thirdparty_title != "" )
			{
				$this->update_formtitle($shortcode_name , $thirdparty_title , $thirdparty_form_type );
			}
			$shortcodedata['module'] =  $module;
			$shortcodedata['crm_type'] =  $activatedplugin;
			$shortcodedata['name'] = $shortcode_name;
			$shortcodedata['type'] = sanitize_text_field($_REQUEST['formtype']);
			$shortcodedata['errormesg'] = sanitize_text_field($_REQUEST['errormessage']);
			$shortcodedata['successmesg'] = sanitize_text_field($_REQUEST['successmessage']);
			$shortcodedata['duplicate_handling'] = sanitize_text_field($_REQUEST['duplicate_handling']);
			if( sanitize_text_field($_REQUEST['enableurlredirection']) == "true" )
			{
				$shortcodedata['isredirection'] = 1;
			}
			else
			{
				$shortcodedata['isredirection'] = 0;
			}
			$shortcodedata['urlredirection'] = sanitize_text_field($_REQUEST['redirecturl']);
			if( sanitize_text_field($_REQUEST['enablecaptcha']) == "true" )
			{
				$shortcodedata['captcha'] = 1;
			}
			else
			{
				$shortcodedata['captcha'] = 0;
			}
			$shortcodeObj->formShorcodeManager( $shortcodedata , "edit" );
			break;
		}
	}
}

class AjaxActionsClassPRO
{
	public static function adminAllActionsPRO()
	{
		check_ajax_referer('smack-leads-builder-crm-key', 'securekey');
		$OverallFunctionObj = new OverallFunctionsPRO();
		if( isset($_REQUEST['operation']) && (sanitize_text_field($_REQUEST['operation']) == "NoFieldOperation") ) {
			$OverallFunctionObj->doNoFieldAjaxAction( );
		} else {
			$OverallFunctionObj->doFieldAjaxAction();
		}
		die;
	}
}

$lb_pages_list = array('lb-crmforms' , 'lb-formsettings' , 'lb-usersync' , 'lb-ecominteg','lb-droptable' , 'lb-crmconfig' , 'lb-oppurtunities' , 'lb-customerstats' , 'lb-reports' , 'lb-dashboard' , 'lb-campaign' , 'lb-create-leadform' , 'lb-create-contactform' , 'lb-usermodulemapping' , 'lb-mailsourcing' , 'wp-leads-builder-any-crm','wp-pro-page','wp-hireus-page');

$page =isset($_REQUEST['page'])?sanitize_text_field($_REQUEST['page']):'';
if ( isset($page) && in_array($page , $lb_pages_list) ) {
	add_action('wp_ajax_adminAllActionsPRO', array( "AjaxActionsClassPRO" , 'adminAllActionsPRO' ));
}

class CapturingProcessClassPRO
{
	function CaptureFormFields( $globalvariables )
	{
		global $wpdb;
		$module_fields=[];
		$HelperObj = new WPCapture_includes_helper_PRO();
		$module = $HelperObj->Module;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$duplicate_inserted = 0;
		$module = $globalvariables['formattr']['module'];
		$post = $globalvariables['post'];
		if($activatedplugin == 'joforce'){
			$FunctionsObj = new joforceFunctions();
		}else{
			$FunctionsObj = new mainCrmHelper();
		}

		$emailfield = $FunctionsObj->duplicateCheckEmailField();
		$shortcode_name = $globalvariables['attrname'];
		$enable_round_robin = $wpdb->get_var( $wpdb->prepare( "select assigned_to from wp_smackleadbulider_shortcode_manager where shortcode_name =%s" , $shortcode_name ) );	
		if( $enable_round_robin == 'Round Robin')	
		{
			$assignedto_old = $wpdb->get_var( $wpdb->prepare( "select Round_Robin from wp_smackleadbulider_shortcode_manager where shortcode_name =%s" , $shortcode_name ) );
		}

		if(is_array($post))
		{
			foreach($post as $key => $value)
			{
				if(($key != 'moduleName') && ($key != 'submitcontactform') && ($key != 'submitcontactformwidget') && ($key != '') && ($key != 'submit'))
				{
					$module_fields[$key] = $value;
					if($key == $emailfield)
					{
						$email_field_present = "yes";
						$user_email = $value;
					}
				}
			}
		}

		if(is_array($post))
		{
			foreach($post as $key => $value)
			{
				if(($key != 'moduleName') && ($key != 'submitwpform') && ($key != 'submitwpformwidget') && ($key != '') && ($key != 'submit'))
				{
					$module_fields[$key] = $value;
					if($key == $emailfield)
					{
						$email_field_present = "yes";
						$user_email = $value;
					}
				}
			}
		}
		if( $enable_round_robin != 'Round Robin' )
		{
			$module_fields[$FunctionsObj->assignedToFieldId()] = $globalvariables['assignedto'];
		}
		else
		{
			$module_fields[$FunctionsObj->assignedToFieldId()] = $assignedto_old;
		}
		unset($module_fields['formnumber']);
		unset($module_fields['IsUnreadByOwner']);

		unset($module_fields['_wpnonce']);
		unset($module_fields['_wp_http_referer']);
		unset($module_fields['submitcontactform']);

		//Check both module and Skip
		// $duplicate_option_check = $globalvariables['formattr']['duplicate_handling'];

		$result_id = $FunctionsObj->result_ids;
		$result_emails = $FunctionsObj->result_emails;
		if($globalvariables['formattr']['duplicate_handling'] == 'update')
		{
			foreach( $result_emails as $key => $email )
			{
				if( ($email == $user_email) && ( $user_email != "" ) )
				{
					$ids_present = $result_id[$key];
					$email_present = "yes";
				}
			}

			$record = $FunctionsObj->createRecord( $module , $module_fields);
			if($record['result'] == "success")
			{
				$duplicate_inserted++;
				$data = "/$module entry is added./";
				if( $enable_round_robin == 'Round Robin' )
				{
					$new_assigned_val = self::getRoundRobinOwner( $assignedto_old );	
					$wpdb->update( 'wp_smackleadbulider_shortcode_manager' , array( 'Round_Robin' => $new_assigned_val ) , array( 'shortcode_name' => $shortcode_name ) );
				}
			}

		}
		else
		{

			$users_list = get_option('crm_users');
			$users_list = $users_list[$activatedplugin];
			if($activatedplugin == 'wpsalesforcepro'){
				$module_fields['OwnerId'] = $users_list['id'][0];
				if(isset($module_fields['submitcontactformwidget'])){
					unset($module_fields['submitcontactformwidget']);
				}
				
			}
			$record = $FunctionsObj->createRecord( $module , $module_fields);

			$data = "failure";
			if($record['result'] == "success")
			{
				$duplicate_inserted++;
				$data = "/$module entry is added./";

				if( $enable_round_robin == 'Round Robin' )
				{
					$new_assigned_val = self::getRoundRobinOwner( $assignedto_old );
					$wpdb->update( 'wp_smackleadbulider_shortcode_manager' , array( 'Round_Robin' => $new_assigned_val ) , array( 'shortcode_name' => $shortcode_name ) );
				}
			}
		}

		return $data;
	}
	public static function thirdparty_mapped_submission($posted_array)
	{

		$tp_module = $posted_array['third_module'];
		$tp_shortcode = $posted_array['shortcode'];

		//Code For RR
		$get_existing_option = get_option( $tp_shortcode );
		$tp_assignedto = $get_existing_option['thirdparty_assignedto'];
		$assignedto_old = isset($get_existing_option['tp_roundrobin']) ? $get_existing_option['tp_roundrobin'] :'';
		$wp_active_crm = get_option( 'WpLeadBuilderProActivatedPlugin' );

		if( empty( $assignedto_old))
		{
			if($wp_active_crm == 'joforce'){
				$get_first_RR_owner = new joforceFunctions();
			}else{
				$get_first_RR_owner = new mainCrmHelper();
			}

			$get_first_user = $get_first_RR_owner->getUsersList();
			$assignedto_old = $get_first_user['id'][0];
			$get_existing_option['tp_roundrobin'] = $assignedto_old;
			update_option( $tp_shortcode , $get_existing_option );
		}

		//END RR


		if( isset($tp_module)  )
		{
			$module = $tp_module;
			if($wp_active_crm == 'joforce'){
				$FunctionsObj = new joforceFunctions();
			}
			else{
				$FunctionsObj = new mainCrmHelper();
			}

			$post = $posted_array['posted'];
			$Assigned_user = CapturingProcessClassPRO::wp_get_mapping_assignedto($tp_shortcode , $assignedto_old);
			$Assigned_user_value = array_values($Assigned_user);
			$Assigned_user_value[0] = isset($Assigned_user_value[0]) ? $Assigned_user_value[0] :'';
			if( $Assigned_user_value[0] != "--Select--" )
			{
				$post = array_merge( $post , $Assigned_user );
			}else{
				$assign_user_key = array_keys($Assigned_user);
				$get_crm_users = get_option('crm_users');
				$crmuserid = $get_crm_users['wpzohopro']['id'][0];
				$assign_user = array();
				$assign_user[$assign_user_key[0]] = $crmuserid;
				$post = array_merge( $post , $assign_user);
			}
			
			$users_list = get_option('crm_users');
			$users_list = $users_list[$wp_active_crm];
			if($wp_active_crm == 'wpsalesforcepro'){
				$post['OwnerId'] = $users_list['id'][0];
			}
			$record = $FunctionsObj->createRecord( $module , $post);
			if($record['result'] == "success")
			{
				if( $tp_assignedto == 'Round Robin' )
				{
					$new_assigned_val = self::getRoundRobinOwner( $assignedto_old );
					$get_existing_option['tp_roundrobin'] = $new_assigned_val;
					update_option( $tp_shortcode , $get_existing_option);
				}
			}
		}
	}

	//Register new user

	public static function mapRegisterUser( $module , $user_id, $posted_fields , $assignedto_old )
	{
		$usersync_active_crm = get_option( "WpLeadBuilderProActivatedPlugin" );
		$user_field_map = get_option("User{$usersync_active_crm}{$module}ModuleMapping");
		$user_data = get_userdata( $user_id );
		$user_meta = get_user_meta( $user_id );
		$user_fields = array( 'user_login' => __('Username', "wp-leads-builder-any-crm-pro") , 'role' => __('Role' , "wp-leads-builder-any-crm-pro" ) , 'user_nicename' => __('Nicename' , "wp-leads-builder-any-crm-pro" ) , 'user_email' => __('E-mail', "wp-leads-builder-any-crm-pro" ) , 'user_url' => __('Website', "wp-leads-builder-any-crm-pro" ) , 'display_name' => __('Display name publicly as', "wp-leads-builder-any-crm-pro" ) );
		$user_meta_field = array( 'nickname' => __('Nickname', "wp-leads-builder-any-crm-pro" ) , 'first_name' => __('First Name', "wp-leads-builder-any-crm-pro" ) , 'last_name' => __('Last Name', "wp-leads-builder-any-crm-pro" ) , 'description' => __('Biographical Info', "wp-leads-builder-any-crm-pro" ) );
		$post=[];
		foreach( $user_fields as $field_name => $Label )
		{
			if( $user_field_map[$field_name] != "" )
			{
				$post[$user_field_map[$field_name]] = $user_data->data->$field_name;
			}
		}

		foreach( $user_meta_field as $field_name => $Label )
		{
			if( $user_field_map[$field_name] != "" )
			{
				$post[$user_field_map[$field_name]] = $user_meta[$field_name][0];
			}
		}

		if( $user_field_map['role'] != "" )
		{
			$post[$user_field_map['role']] = $user_data->roles[0];
		}
		return $post;
	}

	function getRoundRobinOwner( $assignedto_old )
	{

		$crm_users_list = get_option( 'crm_users' );
		$activated_crm = get_option( 'WpLeadBuilderProActivatedPlugin' );
		$RR_users_list = $crm_users_list[$activated_crm];
		$RR_users_id = $RR_users_list['id'];

		foreach( $RR_users_id as $RR_key => $RR_val )
		{
			$i = $RR_key;
			if( $assignedto_old == $RR_val )
			{
				if( isset( $RR_users_id[$i+1] ))
				{
					$assignedto_new = $RR_users_id[$i+1];
				}
				else
				{
					$assignedto_new = $RR_users_id[0];
				}
			}

			$i++;
		}
		return $assignedto_new;

	}
	public static function wp_get_mapping_assignedto($shortcode , $assignedto_old)
	{

		$wp_active_crm = get_option( 'WpLeadBuilderProActivatedPlugin' );
		$wp_assigneduser_config = get_option( $shortcode );
		$module = $wp_assigneduser_config['third_module'];
		$tp_assignedto = $wp_assigneduser_config['thirdparty_assignedto'];		
		$assignedto_user = array();
		switch( $wp_active_crm )
		{
		case 'wpzohopro':
			if( $tp_assignedto != 'Round Robin' )
			{
				$assignedto_user['SMOWNERID'] = $wp_assigneduser_config['thirdparty_assignedto'];	
			}
			else
			{
				$assignedto_user['SMOWNERID'] = $assignedto_old;
			}
			break;

		case 'wpzohopluspro':
			if( $tp_assignedto != 'Round Robin' )
			{
				$assignedto_user['SMOWNERID'] = $wp_assigneduser_config['thirdparty_assignedto'];
			}
			else
			{
				$assignedto_user['SMOWNERID'] = $assignedto_old;
			}
			break;

		case 'wpsugarpro':
			if( $tp_assignedto != 'Round Robin' )
			{
				$assignedto_user['assigned_user_id'] = $wp_assigneduser_config['thirdparty_assignedto'];	
			}
			else
			{
				$assignedto_user['assigned_user_id'] = $assignedto_old;
			}
			break;

		case 'wpsuitepro':
			if( $tp_assignedto != 'Round Robin' )
			{
				$assignedto_user['assigned_user_id'] = $wp_assigneduser_config['thirdparty_assignedto'];

			}
			else
			{
				$assignedto_user['assigned_user_id'] = $assignedto_old;

			}
			break;


		case 'wptigerpro':
			if( $tp_assignedto != 'Round Robin' )
			{
				$assignedto_user['assigned_user_id'] = $wp_assigneduser_config['thirdparty_assignedto'];	
			}
			else
			{
				$assignedto_user['assigned_user_id'] = $assignedto_old;
			}

			break;

		case 'wpsalesforcepro':
			if( $tp_assignedto != 'Round Robin' )
			{
				$assignedto_user['OwnerId'] = $wp_assigneduser_config['thirdparty_assignedto'];	
			}
			else
			{
				$assignedto_user['OwnerId'] = $assignedto_old;
			}
			break;

		case 'freshsales':
			if( $tp_assignedto != 'Round Robin' )
			{
				$assignedto_user['owner_id'] = $wp_assigneduser_config['thirdparty_assignedto'];
			}
			else
			{
				$assignedto_user['owner_id'] = $assignedto_old;
			}
			break;


		}
		return $assignedto_user;
	}

	/*
	Capture wordpress user on registration or creating a user from Wordpress Users
	 */
	//Register new user
	public static function capture_registering_users($user_id)
	{
		$wp_assigneduser_config=[];
		$posted_custom_fields = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
		$HelperObj = new WPCapture_includes_helper_PRO();
		$module = "Leads";
		$moduleslug = $HelperObj->ModuleSlug;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;
		$config_user_capture = get_option("smack_{$activatedplugin}_user_capture_settings");

		//Code For RR
		$wp_active_crm = get_option( 'WpLeadBuilderProActivatedPlugin' );
		if( empty( $assignedto_old))
		{
			if($wp_active_crm == 'joforce'){
				$get_first_usersync_owner = new joforceFunctions();
			}else{
				$get_first_usersync_owner = new mainCrmHelper();
			}

			$get_first_user = $get_first_usersync_owner->getUsersList();
			$assignedto_old = $get_first_user['id'][0];
			$wp_assigneduser_config['usersync_rr_value'] = $assignedto_old;
			update_option( "smack_{$wp_active_crm}_usersync_assignedto_settings" , $wp_assigneduser_config );       
		}
		if($wp_active_crm == 'joforce'){
			$FunctionsObj = new joforceFunctions();
		}else{
			$FunctionsObj = new mainCrmHelper();
		}

		$user_email = "";
		// $duplicate_option_check = $config_user_capture['smack_capture_duplicates'];
		$user_data = get_userdata( $user_id );
		$user_email = $user_data->data->user_email;
		$user_lastname = get_user_meta( $user_id, 'last_name', 'true' );
		// $user_firstname = get_user_meta( $user_id, 'first_name', 'true' );
		if(empty($user_lastname))
		{
			$user_lastname = $user_data->data->display_name;
		}
		$post = array();
		$post = CapturingProcessClassPRO::mapRegisterUser( $module , $user_id , $posted_custom_fields , $assignedto_old );
		switch( $wp_active_crm)	{

		case 'joforce':
		case 'wptigerpro':
		case 'wpsugarpro':
		case 'wpsuitepro':
			$post['assigned_user_id'] = $assignedto_old;
			break;

		case 'wpzohopro':
		case 'wpzohopluspro':
			$post['SMOWNERID'] = $assignedto_old;
			break;

		case 'wpsalesforcepro':
			$post['OwnerId'] = $assignedto_old;	
			break;

		case 'freshsales':
			$post['owner_id'] = $assignedto_old;
			break;
		}
		$post[$FunctionsObj->duplicateCheckEmailField()] = $user_email;	
		$record = $FunctionsObj->createRecord( $module , $post);
	}
}
