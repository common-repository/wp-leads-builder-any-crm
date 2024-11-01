<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

class SyncUserActions {

    public function __construct()
    {
    }

    public function ModuleMapping( $user_fields,$config_fields,$option)
    {
	$HelperObj = new WPCapture_includes_helper_PRO();
       	$Activated_plugin = $HelperObj->ActivatedPlugin;
	$config = get_option("smack_{$Activated_plugin}_user_capture_settings");
	if(!empty($user_fields)) {
	$add_user_array = update_option("smack_{$Activated_plugin}_userfields_capture_settings",$user_fields);}
	$module = isset($config['user_sync_module'])?$config['user_sync_module']:'';
	$request = "";
       	$data = $this->UserModuleMapping( $user_fields , $config_fields, $module ,$option  );
       	return $data;
    }

    public function UserModuleMapping( $user_fields , $config_fields , $module , $option )
    {
		// return an array of name value pairs to send data to the template
		$module='Leads';
	    $data = array();
		   if(!empty($config_fields)) { 
		   foreach( $config_fields as $REQUESTS_KEY => $REQUESTS_VALUE )
		    {
			    $data['REQUEST'][$REQUESTS_KEY] = $REQUESTS_VALUE;
		    }}

	    $data['config_fields'] = $config_fields;
	    $data['HelperObj'] = new WPCapture_includes_helper_PRO();
	    $data['module'] = $module ;
	    $data['moduleslug'] = rtrim( strtolower($module) , "s");
	    $data['activatedplugin'] = $data['HelperObj']->ActivatedPlugin;
	    $data['activatedpluginlabel'] = $data['HelperObj']->ActivatedPluginLabel;
	    $data['plugin_dir']= SM_LB_PRO_DIR;
	    $data['plugins_url'] = plugins_url('',dirname(__FILE__,1));
	    $data['siteurl'] = site_url();
	    if($option == "update") 
	    {
		    $this->saveUserMapping($data);
		    $data['display'] = "<p class='display_success'> Settings Saved Successfully</p>";
	    }
	    $activated_plugin = get_option( "WpLeadBuilderProActivatedPlugin" );
	    $data['UserModuleMapping'] = get_option("User{$data['activatedplugin']}{$data['module']}ModuleMapping");;
	    $CaptureDataObj = new CaptureData();
	    $leadFields = $CaptureDataObj->get_crmfields_by_settings( $data['activatedplugin'] , $data['module'] );
	    $data['fields'] = $leadFields;
	    return $data;
    }

    public function saveUserMapping( $data )
    {
	    $activated_plugin = get_option( "WpLeadBuilderProActivatedPlugin" );
	    $userfield = get_option("smack_{$activated_plugin}_userfields_capture_settings");
	    $module_field = get_option("smack_{$activated_plugin}_mappedfields_capture_settings");
	    $mapfields = array();
	    foreach($userfield as $key => $value)
	    {
		$mapfields[] = $key;
	    }
	    $combined_fields = array_combine($mapfields,$module_field);
	    update_option( "User{$activated_plugin}{$data['module']}ModuleMapping" , $combined_fields );
    }
}

