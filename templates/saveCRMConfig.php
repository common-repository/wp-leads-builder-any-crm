<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class SaveCRMConfigActions {

	public function __construct()
	{
	}
	public function saveConfigAjax()
	{
		$data=[];
		$request = $_REQUEST['posted_data'];
		foreach($request as $requestKey => $requestVal){
			if($requestKey == 'url'){
				$data['REQUEST'][$requestKey]= sanitize_url($requestVal);
			}
			else if($requestKey == 'site_url'){
				$data['REQUEST'][$requestKey]= sanitize_url($requestVal);	
			}
			else{
				$data['REQUEST'][$requestKey]= sanitize_text_field($requestVal);	
			}
		}
		$data['HelperObj'] = new WPCapture_includes_helper_PRO();
		$data['module'] = $data['HelperObj']->Module;
		$data['moduleslug'] = $data['HelperObj']->ModuleSlug;
		if($data['REQUEST']['active_plugin'] == 'wpsuitepro'){
			$data['activatedplugin'] = 'wpsuitepro';
		}elseif($data['REQUEST']['active_plugin'] == 'joforce'){
			$data['activatedplugin'] = 'joforce';
		}
		else{
			$data['activatedplugin'] = $data['HelperObj']->ActivatedPlugin;
		}
		$data['activatedpluginlabel'] = $data['HelperObj']->ActivatedPluginLabel;
		$data['option'] = $data['options'] = "smack_{$data['activatedplugin']}_{$data['moduleslug']}_fields-tmp";
		$crmslug = str_replace( "pro" , "" , $data['activatedplugin'] );
		$crmslug = str_replace( "wp" , "" , $crmslug );
		$data['crm'] = $crmslug;
		$data['action'] = $data['activatedplugin']."Settings";
		if( isset($data['REQUEST']["posted"]) && ($data['REQUEST']["posted"] == "posted") )
		{
			$result = $this->saveSettings( $data );
			if($result['error'] == 1)
			{
				$data['display'] = $result['errormsg'];
			}
			else if( $result['error'] == 11 )
			{
				$data['display'] = $result['errormsg'];
			}
			else
			{
				$data['display'] = "Settings Successfully Saved";
			}
			$final_result=[];
			$final_result['display'] = $data['display'];
			$final_result['error'] = $result['error'];
			// $final_result = json_encode( $final_result );
			echo wp_json_encode($final_result);
			die;
		}
	}

	public function saveSettings( $request )
	{
		update_option("WpLeadBuilderProFirstTimeWarning" , "false");
		include( SM_LB_PRO_DIR .'templates/SaveConfigHelper.php');
		$saveCall = new SaveCRMConfig();
		$result = $saveCall->CheckCRMType( $request );
		return $result;
	}
}
$saveObj = new SaveCRMConfigActions();
$call = $saveObj->saveConfigAjax();
