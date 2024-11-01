<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
?>
<div class="mt30">
<div class="panel_crm_form">
<div class="panel-body" style="padding:8px">

<?php
require_once( SM_LB_PRO_DIR."includes/Functions.php" );
$OverallFunctionsPROObj = new OverallFunctionsPRO();
$page = sanitize_text_field($_REQUEST['page']);
$result = $OverallFunctionsPROObj->CheckFetchedDetails();
$allowed_html = ['div' => ['class' => true, 'id' => true, 'style' => true, ], 
	'a' => ['id' => true, 'href' => true, 'title' => true, 'target' => true, 'class' => true, 'style' => true, 'onclick' => true,], 
	'strong' => [], 
	'i' => ['id' => true, 'onclick' => true, 'style' => true, 'class' => true, 'aria-hidden' => true, 'title' => true ], 
	'p' => ['style' => true, 'name' => true, 'id' => true, ], 
	'img' => ['id' => true, 'style' => true, 'class' => true, 'src' => true, 'align' => true, 'src' => true, 'width' => true, 'height' => true, 'border' => true, ], 
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
	'option' => ['value' => true, 'selected' => true, 'disabled' => true],
	'label' =>['id' => true, 'class' =>true],
	'input' => ['type' => true, 'value' => true, 'id' => true, 'name' => true, 'class' => true, 'onclick' => true],
	'form' => ['method' => true, 'name' => true, 'id' => true, 'action' => true]];
if( !$result['status'] )
{
	$display_content = "<br>". $result['content']." to create Forms <br><br>";
	$content = "<div style='font-weight:bold;  color:red; font-size:16px;text-align:center'> $display_content </div>";
	echo wp_kses($content,$allowed_html);
}
else
{
	global $crmdetailsPRO;
	global $attrname;
	global $migrationmap;
	global $wpdb;
	global $lb_crmm;
	require_once( SM_LB_PRO_DIR."includes/class_lb_manage_shortcodes.php" );
	$HelperObj = new WPCapture_includes_helper_PRO();
	$module = $HelperObj->Module;
	$moduleslug = $HelperObj->ModuleSlug;
	$activatedplugin = $HelperObj->ActivatedPlugin;
	$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;
	$active_plugins_hook = get_option( "active_plugins" );
	$redirect_to = "no";

	switch ($activatedplugin) {
	case 'wpzohopluspro':
	case 'wpzohopro':
		if( !in_array( "wp-zoho-crm/index.php" , $active_plugins_hook) )       {
			$redirect_to = 'yes';
		}
		break;
	case 'freshsales':
		if( !in_array( "wp-freshsales/index.php" , $active_plugins_hook) )       {
			$redirect_to = 'yes';
		}
		break;
	case 'wpsalesforcepro':
		if( !in_array( "wp-salesforce/index.php" , $active_plugins_hook) )       {
			$redirect_to = 'yes';
		}
		break;
	case 'wptigerpro':
		if( !in_array( "wp-tiger/index.php" , $active_plugins_hook) )       {
			$redirect_to = 'yes';
		}
		break;
	case 'wpsugarpro':
		if( !in_array( "wp-sugar-free/index.php" , $active_plugins_hook) )       {
			$redirect_to = 'yes';
		}
		break;

	default:
		# code...
		break;
	}
	if($redirect_to == 'yes'){
		$contents = "<div style='  font-size:16px;text-align:center'> Please <a href='admin.php?page=wp-leads-builder-any-crm'>configure </a> your CRM </div>";
		echo wp_kses($contents,$allowed_html);
		die();
	}
	$lb_crmm->setActivatedPluginLabel($activatedpluginlabel);
	$plugin_url= SM_LB_PRO_DIR;
	$lb_crmm->setPluginsUrl($plugin_url);
	$onAction= 'onCreate';
	$siteurl= site_url();
	$crm_users = get_option("crm_users");
	$users_detail = array();
	foreach( $crm_users[$activatedplugin]['id'] as $key => $value )
	{
		$users_detail[$value] = array( 'user_name' => $crm_users[$activatedplugin]['user_name'][$key] , 'first_name' => $crm_users[$activatedplugin]['first_name'][$key] , 'last_name' => $crm_users[$activatedplugin]['last_name'][$key]  );
	}


	$content1 = "";
	$content1 .= "<div class='leads-builder-heading col-md-12 mb20' style='margin-left:30%;display:flex;align-items: center;'><h4>".__('Form Shortcodes Table' , "wp-leads-builder-any-crm" )."</h4> ( {$crmdetailsPRO[$activatedplugin]['Label']} ) </div>
				  <div class='wp-common-crm-content'>
				  <table class='form_table'>
				  <tr style='border-top: 1px solid #dddddd;'>
				  </tr>
				  <tr class='smack-crm-pro-highlight smack-crm-pro-alt' style='border-top: 1px solid #dddddd;'>
				  <th class='smack-crm-free-list-view-th' style='width: 300px;'>".__('Shortcode / Title' , 'wp-leads-builder-any-crm' )."</th>
				  <th class='smack-crm-free-list-view-th' style='width: 200px;'>".__('Assignee' , 'wp-leads-builder-any-crm' )."</th>
				  <th class='smack-crm-free-list-view-th' style='width: 200px;'>".__('Module' , 'wp-leads-builder-any-crm' )."</th>
				  <th class='smack-crm-free-list-view-th' style='width: 200px;'>".__('Thirdparty' , 'wp-leads-builder-any-crm' )."</th>			
				  <th class='smack-crm-free-list-view-th' style='width: 200px;'>".__('Actions' , 'wp-leads-builder-any-crm' )."</th>
				  </tr>";

	$shortcodemanager = $wpdb->get_results("select *from wp_smackleadbulider_shortcode_manager where crm_type = '{$activatedplugin}'");
	if($activatedplugin == 'joforce'){
		$assign_helper = new joforceFunctions();
	}else{
		$assign_helper = new mainCrmHelper();
	}

	$assignto = $assign_helper->getUsersList();
	if($assignto==""){
		wp_redirect('admin.php?page=lb-crmconfig'); die();
	}
	foreach($shortcodemanager as $shortcode_fields)
	{
		$content1 .= "<tr>";
		// $shortcode_name = "[" . $shortcode_fields->crm_type . "-web-form name='" . $shortcode_fields->shortcode_name . "']";
		$shortcode_name = "[" . $shortcode_fields->crm_type . "-web-form name='" . $shortcode_fields->shortcode_name . "' module='".$shortcode_fields->module."']";

		if( $shortcode_fields->assigned_to == "Round Robin" )
		{
			$assigned_to = "Round Robin";
		}
		else
		{
			$assigned_to=$assignto['first_name'][0]." ".$assignto['last_name'][0];
		}
		$oldshortcodename = "";
		$oldshortcode_reveal_html = "";
		$oldshortcode_html = "";
		if( $shortcode_fields->old_shortcode_name != NULL )
		{
			$oldshortcodename = $shortcode_fields->old_shortcode_name;
			$oldshortcode_reveal_html = "<p><a style='cursor:pointer;' id='oldshortcodename_reveal{$shortcode_fields->shortcode_id}' onclick='jQuery(\"#oldshortcodename\"+{$shortcode_fields->shortcode_id}).show(); jQuery(\"#oldshortcodename_reveal\"+{$shortcode_fields->shortcode_id}).hide(); '> Click here to reveal old shortcode </a></p>";
			$oldshortcode_html = "<p style='display:none;' id='oldshortcodename{$shortcode_fields->shortcode_id}'> $oldshortcodename </p>";
		}
		$content1 .= "<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'>" . $shortcode_name . "$oldshortcode_reveal_html $oldshortcode_html</td>";
		$content1 .= "<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'>" . $assigned_to . "</td>";
		$content1 .= "<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'>" . $shortcode_fields->module . "</td>";
		$content1 .= "<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> None </td>";
		$content1 .= "<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align:center;'>";
		$content1 .= "<a href='#' onclick='create_form(\"Editshortcode\" , \"$shortcode_fields->module\" , \"$shortcode_fields->shortcode_name\",\"$activatedplugin\")'> <i class='icon-pencil2'></i> </a>";
		$content1 .= "<i style='margin-left:2px;' class='icon-trash2' title='Upgrade to PRO' disabled></i>";
		$content1 .= "</td>";
		$content1 .= "</tr>";
	}

	$existing_content = '';
	$save_gravity_form_id = array();
	$gravity_option_name = $activatedplugin."_wp_gravity";
	$list_of_shortcodes = $wpdb->get_results( $wpdb->prepare( "select option_name from {$wpdb->prefix}options where option_name like %s" , "$gravity_option_name%" ) );
	if( !empty( $list_of_shortcodes ))
	{
		foreach( $list_of_shortcodes as $list_key => $list_val )
		{
			$shortcode_name = $list_val->option_name;
			$form_id = explode( $gravity_option_name , $shortcode_name );
			$save_gravity_form_id[] = $form_id[1];

		}
	}
	foreach( $save_gravity_form_id as $grav_val )
	{
		$get_config = get_option($gravity_option_name."".$grav_val);
		$exist_module = $get_config['third_module'];
		$exist_assignee = $get_config['thirdparty_assignedto_name'];
		$get_form_title = $wpdb->get_results( $wpdb->prepare( "select title from {$wpdb->prefix}rg_form where id=%d" , $grav_val ) );
		$gravity_form_title = $get_form_title[0]->title;
		$third_plugin = $get_config['third_plugin'];
		if(isset($get_config['tp_roundrobin'])) {
			$third_roundrobin = $get_config['tp_roundrobin'];
		} else {
			$third_roundrobin = "";
		}

		$existing_content .= "<tr>
			<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $gravity_form_title</td>
			<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_assignee</td>				
			<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_module</td>
			<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> Gravity Form</td>
			<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'>"; 
		$existing_content .= "<a href='#' class='modal_open_exist' onclick='return show_map_config(\"$exist_module\" , \"$gravity_form_title\" , \"$grav_val\" , \"$third_plugin\" , \"$third_roundrobin\")'> <i class='icon-pencil2'></i> </a>";
		$existing_content .= "<i style='margin-left:2px;' class='icon-trash2'></i>";

		$existing_content .="</td></tr>";
	}

	//NINJA MAPPED FIELDS
	$save_ninja_form_id = array();
	$ninja_option_name = $activatedplugin."_wp_ninja";
	$list_of_shortcodes = $wpdb->get_results( $wpdb->prepare( "select option_name from {$wpdb->prefix}options where option_name like %s" , "$ninja_option_name%" ) );
	if( !empty( $list_of_shortcodes ))
	{
		foreach( $list_of_shortcodes as $list_key => $list_val )
		{
			$shortcode_name = $list_val->option_name;
			$form_id = explode( $ninja_option_name , $shortcode_name );
			$save_ninja_form_id[] = $form_id[1];

		}
	}

	foreach( $save_ninja_form_id as $ninja_val )
	{
		$get_config = get_option($ninja_option_name."".$ninja_val);
		$exist_module = $get_config['third_module'];
		$exist_assignee = $get_config['thirdparty_assignedto_name'];
		$get_form_title = $wpdb->get_results( $wpdb->prepare( "select title from {$wpdb->prefix}nf3_forms where id=%d" , $ninja_val ) );
		$ninja_form_title = $get_form_title[0]->title;
		$third_plugin = $get_config['third_plugin'];
		if(isset($get_config['tp_roundrobin'])) {
			$third_roundrobin = $get_config['tp_roundrobin'];
		} else {
			$third_roundrobin = "";
		}
		$existing_content .= "<tr><td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $ninja_form_title</td>
			<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_assignee</td>				
			<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_module</td>
			<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> Ninja Forms</td>
			<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'>"; 
		$existing_content .= "<a href='#' class='modal_open_exist' onclick='return show_map_config(\"$exist_module\" , \"$ninja_form_title\" , \"$ninja_val\" , \"$third_plugin\" , \"$third_roundrobin\")'> <i class='icon-pencil2'></i></a>";
		$existing_content .= "<a href='#' onclick='return delete_map_config(\"$third_plugin\" , \"$ninja_val\");' style='margin-left:2px;'> <i class='icon-trash2'></i> </a>";

		$existing_content .="</td></tr>";		
	}

	//CONTACT FORM MAPPING
	$save_contact_form_id = array();
	$contact_option_name = $activatedplugin."_wp_contact";
	$list_of_shortcodes = $wpdb->get_results( $wpdb->prepare( "select option_name from {$wpdb->prefix}options where option_name like %s" , "$contact_option_name%" ) );
	if( !empty( $list_of_shortcodes ))
	{
		foreach( $list_of_shortcodes as $list_key => $list_val )
		{
			$shortcode_name = $list_val->option_name;
			$form_id = explode( $contact_option_name , $shortcode_name );
			$save_contact_form_id[] = $form_id[1];

		}
	}

	foreach( $save_contact_form_id as $contact_val )
	{
		if(isset($save_caldera_form_id)){
			$cal_value=$save_caldera_form_id[0];
		}
		else{
			$cal_value = '';
		}
		$post_value = $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}posts where id like %d" , "$contact_val%" ) );
		$get_cf_config = get_option($contact_option_name."".$contact_val);
		$exist_module = $get_cf_config['third_module'];
		$exist_assignee = $get_cf_config['thirdparty_assignedto_name'];
		$get_form_title = $wpdb->get_results( $wpdb->prepare( "select post_title from $wpdb->posts where post_type=%s and ID=%d" , 'wpcf7_contact_form' , $contact_val ) );

		$contact_form_title = $get_form_title[0]->post_title;
		$third_plugin = $get_cf_config['third_plugin'];
		if(isset($get_cf_config['tp_roundrobin'])) {
			$third_roundrobin = $get_cf_config['tp_roundrobin'];
		} else {
			$third_roundrobin = "";
		}
		$contact_form_shortcode='[contact-form-7 id="'.$contact_val.'"'.'  title="'.$contact_form_title.'"]';
		if(!empty($post_value)){
			$existing_content .= "<tr>
				<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $contact_form_shortcode</td>
				<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_assignee</td>			
				<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_module</td>	
				<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> Contact Form7</td>
				<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'>";
			$existing_content .= "<a href='#' class='modal_open_exist' onclick='return show_map_config(\"$exist_module\" , \"$contact_form_title\" , \"$contact_val\" , \"$third_plugin\" , \"$third_roundrobin\")'> <i class='icon-pencil2'></i> </a>";
			$existing_content .= "<a href='#' onclick='return delete_map_config(\"$third_plugin\" , \"$contact_val\" );' style='margin-left:2px;'> <i class='icon-trash2'></i> </a>";
		}
		else{
			$option_name=$contact_option_name."".$contact_val;
			delete_option( $option_name );
		}
	}

	// WP FORMS Mapping
	if(in_array( "wpforms-lite/wpforms.php" , $active_plugins_hook)) {
		$save_wpform_form_id = array();
		$wpform_option_name = $activatedplugin."_wp_wpform_lite";
		$list_of_shortcodes = $wpdb->get_results( $wpdb->prepare( "select option_name from {$wpdb->prefix}options where option_name like %s" , "$wpform_option_name%" ) );
		if( !empty( $list_of_shortcodes ))
		{
			foreach( $list_of_shortcodes as $list_key => $list_val )
			{
				$shortcode_name = $list_val->option_name;
				$form_id = explode( $wpform_option_name , $shortcode_name );
				$save_wpform_form_id[] = $form_id[1];

			}
		}

		foreach( $save_wpform_form_id as $wpform_val )
		{
			if(isset($save_caldera_form_id)){
				$cal_value=$save_caldera_form_id[0];
			}
			else{
				$cal_value = '';
			}
			$post_value = $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}posts where id like %d" , "$wpform_val%" ) );
			$get_wp_config = get_option($wpform_option_name."".$wpform_val);
			$exist_module = $get_wp_config['third_module'];
			$exist_assignee = $get_wp_config['thirdparty_assignedto_name'];
			$get_form_title = $wpdb->get_results( $wpdb->prepare( "select post_title from $wpdb->posts where post_type=%s and ID=%d" , 'wpforms' , $wpform_val ) );

			$wpform_form_title = $get_form_title[0]->post_title;
			$third_plugin = $get_wp_config['third_plugin'];
			if(isset($get_wp_config['tp_roundrobin'])) {
				$third_roundrobin = $get_wp_config['tp_roundrobin'];
			} else {
				$third_roundrobin = "";
			}
			$wpforms_shortcode='[wpforms id="'.$wpform_val.'"]';
			if(!empty($post_value)){
				$existing_content .= "<tr>
					<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $wpforms_shortcode</td>
					<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_assignee</td>			
					<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_module</td>	
					<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> WP Forms</td>
					<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'>"; 
				$existing_content .= "<a href='#' class='modal_open_exist' onclick='return show_map_config(\"$exist_module\" , \"$wpform_form_title\" , \"$wpform_val\" , \"$third_plugin\" , \"$third_roundrobin\")'> <i class='icon-pencil2'></i> </a>";
				$existing_content .= "<a href='#' onclick='return delete_map_config(\"$third_plugin\" , \"$wpform_val\" );' style='margin-left:2px;'> <i class='icon-trash2'></i> </a>";
			}
			else{
				$option_name=$wpform_option_name."".$wpform_val;
				delete_option( $option_name );
			}
		}
	}
	//WPFORMPRO MAPPING
	if(in_array( "wpforms/wpforms.php" , $active_plugins_hook)) {
		$save_wpformpro_form_id = array();
		$wpformpro_option_name = $activatedplugin."_wp_wpform_pro";
		$list_of_shortcodes = $wpdb->get_results( $wpdb->prepare( "select option_name from {$wpdb->prefix}options where option_name like %s" , "$wpformpro_option_name%" ) );
		if( !empty( $list_of_shortcodes ))
		{
			foreach( $list_of_shortcodes as $list_key => $list_val )
			{
				$shortcode_name = $list_val->option_name;
				$form_id = explode( $wpformpro_option_name , $shortcode_name );
				$save_wpformpro_form_id[] = $form_id[1];

			}
		}

		foreach( $save_wpformpro_form_id as $wpformpro_val )
		{
			if(isset($save_caldera_form_id)){
				$cal_value=$save_caldera_form_id[0];
			}
			else{
				$cal_value = '';
			}
			$post_value = $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}posts where id like %d" , "$wpformpro_val%" ) );
			$get_config = get_option($wpformpro_option_name."".$wpformpro_val);
			$exist_module = $get_config['third_module'];
			$exist_assignee = $get_config['thirdparty_assignedto_name'];
			$get_form_title = $wpdb->get_results( $wpdb->prepare( "select post_title from $wpdb->posts where post_type=%s and ID=%d" , 'wpforms' , $wpformpro_val ) );
			$wpformpro_form_title = $get_form_title[0]->post_title;
			$third_plugin = $get_config['third_plugin'];
			if(isset($get_config['tp_roundrobin'])) {
				$third_roundrobin = $get_config['tp_roundrobin'];
			} else {
				$third_roundrobin = "";
			}
			$wpformpro_shortcode='[wpforms id="'.$wpformpro_val.'"]';
			if(!empty($post_value)){
				$existing_content .= "<tr>
					<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $wpformpro_shortcode</td>
					<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_assignee</td>			
					<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_module</td>	
					<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> WP Forms Pro</td>
					<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'>"; 
				$existing_content .= "<a href='#' class='modal_open_exist' onclick='return show_map_config(\"$exist_module\" , \"$wpformpro_form_title\" , \"$wpformpro_val\" , \"$third_plugin\" , \"$third_roundrobin\")'> <i class='icon-pencil2'></i> </a>";
				$existing_content .= "<a href='#' onclick='return delete_map_config(\"$third_plugin\" , \"$wpformpro_val\" );' style='margin-left:2px;'> <i class='icon-trash2'></i> </a>";
			}
			else{
				$option_name=$wpformpro_option_name."".$wpformpro_val;
				delete_option( $option_name );
			}
		}
	}
	//CALDERA MAPPED FIELDS
	$save_caldera_form_id = array();
	$caldera_option_name = $activatedplugin."_wp_caldera";
	$list_of_shortcodes = $wpdb->get_results( $wpdb->prepare( "select option_name from {$wpdb->prefix}options where option_name like %s" , "$caldera_option_name%" ) );
	if( !empty( $list_of_shortcodes ))
	{
		foreach( $list_of_shortcodes as $list_key => $list_val )
		{
			$shortcode_name = $list_val->option_name;
			$form_id = explode( $caldera_option_name , $shortcode_name );
			$save_caldera_form_id[] = $form_id[1];

		}
	}

	foreach( $save_caldera_form_id as $caldera_val )
	{
		if(isset($save_caldera_form_id)){
			$cal_value=$save_caldera_form_id[0];
		}
		else{
			$cal_value = '';
		}
		$post_value = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cf_forms where form_id ='$cal_value'",ARRAY_A) ;
		$get_config = get_option($caldera_option_name."".$caldera_val);
		$exist_module = $get_config['third_module'];
		$exist_assignee = $get_config['thirdparty_assignedto_name'];
		$get_form_config = $wpdb->get_results( "SELECT config FROM {$wpdb->prefix}cf_forms WHERE form_id = '$caldera_val' AND type = 'primary' ", ARRAY_A);

		$caldera_forms_config = $get_form_config[0]['config'];
		$caldera_form_config = unserialize($caldera_forms_config);
		$caldera_form_title = $caldera_form_config['name'];
		$third_plugin = $get_config['third_plugin'];
		if(isset($get_config['tp_roundrobin'])) {
			$third_roundrobin = $get_config['tp_roundrobin'];
		} else {
			$third_roundrobin = "";
		}
		$caldera_form_shortcode='[caldera_form id="'.$cal_value.'"]';
		if(!empty($post_value)){
			$existing_content .= "<tr>
				<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $caldera_form_shortcode</td>
				<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_assignee</td>			
				<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> $exist_module</td>	
				<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'> Caldera Forms</td>
				<td class='smack-crm-pro-highlight' style='border-top: 1px solid #dddddd; text-align: center'>"; 
			$existing_content .= "<a href='#' class='modal_open_exist' onclick='return show_map_config(\"$exist_module\" , \"$caldera_form_title\" , \"$caldera_val\" , \"$third_plugin\" , \"$third_roundrobin\")'> <i class='icon-pencil2'></i> </a>";
			$existing_content .= "<a href='#' onclick='return delete_map_config(\"$third_plugin\" , \"$caldera_val\" );' style='margin-left:2px;'> <i class='icon-trash2'></i> </a>";
		}
		else{
			$option_name=$caldera_option_name."".$caldera_val;
			delete_option( $option_name );
		}
	}

	$content1 .= $existing_content;	
	$content1 .= "</table></div>";
	echo wp_kses($content1, $allowed_html);
	?> 
		<div class="col-md-12 text-center" style="margin-left:10px">
			<div class="col-md-4" style="padding:0">
				<input class="create_form_button" type="submit" value="<?php echo esc_attr__('Create Lead Form' , "wp-leads-builder-any-crm" ); ?>" disabled/>
			</div>    
			<div class="col-md-4" style="padding:0">
				<input class="create_form_button" type="submit" value="<?php echo esc_attr__('Create Contact Form' , "wp-leads-builder-any-crm" ); ?>" disabled/>
			</div>    
			<div class="col-md-4">
				<?php 
					if( !empty($get_wp_config) || !empty($get_cf_config) || !empty($get_config) ){ 
						?>	
							<input class="modal_open_use_exist" type="button" id="thirdparty_map" value="<?php echo esc_attr__('Use Existing Form' , "wp-leads-builder-any-crm" ); ?>" />
						<?php 
					} else{ 
						?>
							<input class="" style="padding:8px 15px" type="button" id="thirdparty_map" value="<?php echo esc_attr__('Use Existing Form' , "wp-leads-builder-any-crm" ); ?>" />
						<?php	
					} 
				?>
			</div>
		</div>
		<div class="col-md-12 text-center">
			<div class="col-md-4">
				<a href ="https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html" class="free-notice" target="_blank"><h6 style="color:red"><?php echo esc_html__("Upgrade To Pro*");?></h6>
				</a>
			</div>
			<div class="col-md-4">
				<a href ="https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html" class="free-notice" target="_blank"><h6 style="color:red"><?php echo esc_html__("Upgrade To Pro*");?></h6>
				</a>
			</div>
			<div class="col-md-4">
				<!-- <a href ="https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html" class="free-notice" style="float:right;" target="_blank"><h6 style="color:red"><?php echo esc_html__("Upgrade To Pro");?></h6> -->
				</a>
			</div>
		</div>

		<div id="loading-image" style="display: none; background:url(<?php echo esc_url(plugins_url("assets/images/ajax-loaders.gif",dirname(__FILE__,2)));?>) no-repeat center"><?php echo esc_html__('' , "wp-leads-builder-any-crm"  ); ?> </div>
			<head>
				<meta charset="utf-8">
				<style>
					.closing{
						background-color: rgba(61, 60, 64, 0.54);
						border-radius: 50%;
						border: 2px solid rgba(61, 60, 64, 0.54);
						float: right;
						color: white;
						padding-left: 13px;
						padding-right: 13px;
						font-size: 25px;
						cursor: pointer;
					}
				</style>
			</head>
			<body>
				<?php
				if( !empty($get_wp_config) || !empty($get_cf_config) || !empty($get_config) ){ 
				?>
					<div class="container alert_modal_view" style="width:80%;display:none">		
						<div class="modal fade" id="mapping-modalbox" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content" style='height:250px;'>
									<div class="modal-body">
										<button type="button" class="closing" id="close_map_modal" data-dismiss="modal" onclick="remove_map_contents();" >&times;</button>
										<h4 class="modal-title" style="text-align:center;padding-top:10%;padding-bottom:3%;color:red;">Please Upgrade to Pro to use more than one Thirdparty Forms</h4></br>
										<button type="button" class="upgrade_pro" style="margin-left: 200px;line-height: 1;"><a style="color: #1caf9a;" id="anchor_hover" href ="https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html"  target="blank"><?php echo esc_html__("Upgrade To Pro");?></a></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="container alert_modal_config" style="width:100%;display:none">
					<!-- Trigger the modal with a button -->
					<!-- Modal -->
					<div class="modal fade" id="mapping-modalbox" role="dialog" style="display:block">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" style="margin-left:28%;color:#1caf9a;font-family: 'Roboto', Helvetica, Arial, sans-serif;">Configure an Existing Form</h4>
								</div>
								<div class="modal-body">
									<div id="clear_contents" class="col-md-offset-1">
										<p id='show_form_list'>
											<?php
												require_once( SM_LB_PRO_DIR."templates/thirdparty_mapping.php" );
												$mapping_ui_fields = new thirdparty_mapping();
												$mapping_content = $mapping_ui_fields->get_mapping_config(isset($get_wp_config),isset($get_cf_config),isset($get_config));
												echo wp_kses($mapping_content,$allowed_html);
											?>
										</p>
									</div>	

										<p style='' id="display_form_lists" class='col-md-offset-1'>
										</p>
										<p style='' id="mapping_options" class='col-md-offset-1'>
										</p>

										<form name='mapping_fields' id='mapping_fields' onsubmit="return false;">
											<?php wp_nonce_field('sm-leads-builder'); ?>
											<div id="CRM_field_mapping"  class='col-md-offset-1'>
											</div>
											<div class="modal-footer col-md-12 form-group" style='margin-top:100px;'>
												<button type="button" id="close" class="smack-btn btn-default btn-radius pull-left" style="margin-right:50%" data-dismiss="modal" onclick="remove_map_contents();">Close</button>
												<input type="button" class="pull-right" name="map_crm_fields" value="Configure" id="map_fields" onclick="map_thirdparty_crm_fields();"> 
											</div>
										</form>
								</div>
							</div>

						</div>
					</div>
				</div>
				<?php
				}else{
				?>
				<div class="container" style="width:100%;">
					<!-- Trigger the modal with a button -->
					<!-- Modal -->
					<div class="modal fade" id="mapping-modalbox" role="dialog">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" style="margin-left:28%;color:#1caf9a;">Configure an Existing Form</h4>
								</div>
								<div class="modal-body">
									<div id="clear_contents" class="col-md-offset-1">
										<p id='show_form_list'>
											<?php
												require_once( SM_LB_PRO_DIR."templates/thirdparty_mapping.php" );
												$mapping_ui_fields = new thirdparty_mapping();
												$mapping_content = $mapping_ui_fields->get_mapping_config(isset($get_wp_config),isset($get_cf_config),isset($get_config));
												echo wp_kses($mapping_content,$allowed_html);
											?>
										</p>
									</div>	

										<p style='' id="display_form_lists" class='col-md-offset-1'>
										</p>
										<p style='' id="mapping_options" class='col-md-offset-1'>
										</p>

										<form name='mapping_fields' id='mapping_fields' onsubmit="return false;">
											<?php wp_nonce_field('sm-leads-builder'); ?>
											<div id="CRM_field_mapping"  class='col-md-offset-1'>
											</div>
											<div class="modal-footer col-md-12 form-group" style='margin-top:100px;'>
												<button type="button" id="close" class=" pull-left" style="margin-right:50%" data-dismiss="modal" onclick="remove_map_contents();">Close</button>
												<input type="button" class="pull-right" name="map_crm_fields" value="Configure" id="map_fields" onclick="map_thirdparty_crm_fields();"> 
											</div>
										</form>
								</div>
							</div>

						</div>
					</div>
				</div>
				<?php
				}
				?>
				<div class="container" style="width:100%;">	
					<div class="modal fade" id="smack_delete_modal" style='margin-top:1%;'>
						<div id="overlay"></div>
						<div class="modal-dialog">
							<!-- Modal content-->
							<form class="modal-content" style='height:150px;width:300px;'>
								<div class="modal-body">
									<h5><b><center style='color:red'>Do you want to delete the Shortcode?</center></b></h5>
									<br/>
									<!-- <div class="delete-butrons" style="float:right"> -->
									<button  type="button" onclick="document.getElementById('smack_delete_modal').style.display='none'" class="btn btn-default" style="color:#333;margin-left:20px;padding:6px 25px;"><span>Cancel</span></button>
									<button  type="button" id="modalDeleteId" onclick='deleteNow();' class="btn btn-default" style="margin-left:20px;background-color:#1caf9a;color:#FFFFFF;padding:6px 25px;"><span>Confirm</span></button>
									<!-- </div> -->
								</div>
							</form>
						</div>
					</div>
				</div>	
				<div id="loading-image" style="display: none; background:url(<?php echo esc_url(plugins_url('assets/images/ajax-loaders.gif',dirname(__FILE__,2)));?>) no-repeat center"><?php echo esc_html__('' , "wp-leads-builder-any-crm-pro"  ); ?> </div>
			</body>
		<?php
		}
		?>
				</div>
		</div>
		<div class="card_form" >
			<h2 class="title2" style="font-size:medium;font-weight:bold">WP Leads Builder for CRM PRO*</h2>
			<hr class="divider"/>
			<b style="font-size: small;font-style: italic;color:#1caf9a">* Choose your favorite CRM</b>
			<p style="padding-left: 11%;">Works with Joforce CRM, Zoho CRM, Vtiger CRM, Salesforce CRM, Freshsales, Zoho CRM Plus, SugarCRM and SuiteCRM</p>
			<b style="font-size: small;font-style: italic;color:#1caf9a">* Create New Form or Update Existing Forms</b>
			<div style="padding-left: 11%;"><p>Integrate Contact Form 7, Gravity Form, Ninja Form plugins & our default forms to build CRM Leads/Contacts</p></div>
			<b style="font-size: small;font-style: italic;color:#1caf9a">* Capture all your WordPress users</b> 
			<div style="padding-left: 11%;"><p>Sync WordPress users as Leads or Contacts into the CRM</p></div>
			<b style="font-size: small;font-style: italic;color:#1caf9a">* Integrate with WooCommerce</b> 
			<div style="padding-left: 11%;"><p>Capture the failed order customer information as Leads and successful order customer details as Contacts into the CRM</p></div>
			<a class="cus-button-1" href="https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html?utm_source=plugin&utm_campaign=promo_widget&utm_medium=pro_edition" target="blank">Buy Now!</a>
		</div>
	</div>
<style>
#actual_btn {
  background-color: #1caf9a;;
  color: white;
  padding: 0.7rem;
  font-family: sans-serif;
  border-radius:50px;
  width:600px;
  cursor: pointer;
  margin-top: 1rem;
  margin-left: 50%;
}

#file-chosen{
	padding: 0.7rem;
  font-family: sans-serif;
  border-radius:50px;
  display:block ruby;
  cursor: pointer;
  margin-top: 1rem;
  margin-left: 50%;
}
</style>

<script> 
const actualBtn = document.getElementById('actual-btn');
const fileChosen = document.getElementById('file-chosen');
actualBtn.addEventListener('change', function(){
	fileChosen.textContent = this.files[0].name
})
	$(function(){
		var tmppath ="";
		var value;
		$('#actual-btn').change( function(event) {

			var tmp=event.target.files[0];
			var filename=event.target.files[0]['name'];
			var exten = filename.split('.'); 
			var extension=exten[1];
			if (extension == 'json'){
				document.getElementById("myfile-button").disabled = false;
				if (tmp) {
					// create reader
					var reader = new FileReader();
					reader.readAsText(tmp);
					reader.onload = function(e) {
						// browser completed reading file - display it
						value=e.target.result;
						jQuery.ajax({
						type:'POST',
							url:leads_builder_ajax_object.url,
							data:{
							action: 'import_file',
								'value' :value,
								'filename':filename,
								'securekey' : leads_builder_ajax_object.nonce,
						}
						});
					};
				}   
			}
			else{
				swal('Error!', 'Unsupported File', 'success');
				document.getElementById("myfile-button").disabled = true;


			}
		});      
	});  


</script>
