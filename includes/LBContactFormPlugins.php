<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

require_once(plugin_dir_path(__FILE__).'/../ConfigureIncludedPlugins.php');
class ContactFormPROPlugins
{
	public function getActivePlugin()
	{
		return get_option('WpLeadBuilderProActivatedPlugin');
	}

	public function getThirdPLugin()	{
		return get_option('WpLeadThirdPartyPLugin');
	}

	public function getMappingModule()
	{
		return get_option( 'WpMappingModule' );
	}

	public function mappingModule()
	{
		global $WpMappingModule;
		$html_map = '<span>  <select  name = "mappingmodule" id ="mappingmodule" onchange="mappingModulePRO( this )">';
                   $select_option = "";
                foreach($WpMappingModule as $moduleslug => $modulelabel)
                {
                        if($this->getMappingModule() == $moduleslug )
                        {
                                $select_option .= "<option value='{$moduleslug}' selected=selected > {$modulelabel} </option>";
                        }
                        else
                        {
                                $select_option .= "<option value='{$moduleslug}' > {$modulelabel} </option>" ;
                        }
                }
                $html_map .= $select_option;
                $html_map .= "</select></span>";
                return $html_map;

		
	}

	public function getCustomFieldPlugins( ) {
		global $custom_plugins;
		 $custom_plugin = get_option( "custom_plugin" );
		$htm = "<span>  <select class='selectpicker form-control' name = 'custom_fields' id ='custom_fields'  onchange='selectedcustomPRO( this  )'>";
		  $selected_option = "";
        	  foreach( $custom_plugins as $customslug => $customlabel )
                  {      
             		if( $custom_plugin == $customslug )
			{
                                $selected_option .= "<option value='{$customslug}' selected=selected > {$customlabel} </option>";
                        }
			else
			{	 
                                $selected_option .= "<option value='{$customslug}' disabled> {$customlabel}  </option>";
			}
		  }
                $htm .= $selected_option;
                $htm .= "</select></span>";
                return $htm;
}

	public function get_ecom_assignedto($shortcode_option)
        {
                //Assign Leads And Contacts to User
        $crm_users_list = get_option( 'crm_users' );
        $users_list=[];
        $activated_crm = get_option( 'WpLeadBuilderProActivatedPlugin' );
        $assignedtouser_config = get_option( $shortcode_option );
        $assignedtouser_config_leads = $assignedtouser_config['thirdparty_assignedto'];
        $Assigned_users_list = $crm_users_list[$activated_crm];
        switch( $activated_crm )
        {
        case 'wpzohopro':
                $html_leads = "";
                $html_leads = '<select style="width:150px;" name="mapping_assignedto" id="mapping_assignedto">';
                $content_option_leads = "";
                $content_option_leads = "<option id='select' value='--Select--'>--Select--</option>";
                if(isset($Assigned_users_list['user_name']))
                $count=count($Assigned_users_list['user_name']);
                for($i = 0; $i < $count ; $i++)
                {
                        $content_option_leads.="<option id='{$Assigned_users_list['user_name'][$i]}' value='{$Assigned_users_list['id'][$i]}'";
                        if($Assigned_users_list['id'][$i] == $assignedtouser_config_leads )
                        {
                                $content_option_leads .=" selected";
                        }

                        $content_option_leads .=">{$Assigned_users_list['user_name'][$i]}</option>";
                }
                $html_leads .= $content_option_leads;
                $html_leads .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
                return $html_leads;
                break;

		case 'wptigerpro':
                $html_leads = "";
                $html_leads = '<select style="width:150px;" name="mapping_assignedto" id="mapping_assignedto" style="min-width:69px;">';
                $content_option_leads = "";

                $content_option_leads = "<option id='select' value='--Select--'>--Select--</option>";

                if(isset($Assigned_users_list['user_name']))
                $count=count($Assigned_users_list['user_name']);
                        for($i = 0; $i < $count ; $i++)
                        {
                                $content_option_leads .="<option id='{$Assigned_users_list['id'][$i]}' value='{$Assigned_users_list['id'][$i]}'";
                                if($Assigned_users_list['id'][$i] == $assignedtouser_config_leads)
                                {
                                        $content_option_leads .=" selected";
                                }

                                $content_option_leads .=">{$Assigned_users_list['first_name'][$i]} {$Assigned_users_list['last_name'][$i]}</option>";
                        }
                $html_leads .= $content_option_leads;
                $html_leads .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
                return $html_leads;
                break;
		
		case 'wpsugarpro':
        $html_leads = "";
                $html_leads = '<select style="width:150px;" name="mapping_assignedto" id="mapping_assignedto" style="min-width:69px;">';
                $content_option_leads = "";

                $content_option_leads = "<option id='select' value='--Select--'>--Select--</option>";
                if(isset($Assigned_users_list['user_name']))
                $count=count($Assigned_users_list['user_name']) ;
                for($i = 0; $i < $count; $i++)
                {
                        $content_option_leads .="<option id='{$Assigned_users_list['id'][$i]}' value='{$Assigned_users_list['id'][$i]}'";

                        if($Assigned_users_list['id'][$i] == $assignedtouser_config_leads)
                        {
                                $content_option_leads .=" selected";

                        }

                        $content_option_leads .=">{$Assigned_users_list['first_name'][$i]} {$Assigned_users_list['last_name'][$i]}</option>";
                }
                $html_leads .= $content_option_leads;
                $html_leads .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
                return $html_leads;
                break;

		case 'wpsalesforcepro':
                $html_leads = "";
                $html_leads = '<select style="width:150px;" name="mapping_assignedto" id="mapping_assignedto" style="min-width:69px;">';
                $content_option_leads = "";

                $content_option_leads = "<option id='select' value='--Select--'>--Select--</option>";
                if(isset($users_list['user_name']))
                $count=count($Assigned_users_list['user_name']);
                for($i = 0; $i < $count ; $i++)
                {
                        $content_option_leads .="<option id='{$Assigned_users_list['user_name'][$i]}' value='{$Assigned_users_list['id'][$i]}'";
                        if($Assigned_users_list['id'][$i]== $assignedtouser_config_leads)
                        {
                                $content_option_leads .=" selected";
                        }

                        $content_option_leads .=">{$Assigned_users_list['user_name'][$i]}</option>";
                }
                $html_leads .= $content_option_leads ;
                $html_leads .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
                return $html_leads;
                break;

                }
        }


	public function getPluginActivationHtml( )
	{
		global $IncludedPluginsPRO;
                $html = '<span>  <select class="selectpicker form-control" data-size="5" name = "pluginselect" id ="pluginselect" onchange="selectedPlugPRO( this )">';
                $select_option = "";
                $active_plugins_hook = get_option( "active_plugins" );
        
                foreach($IncludedPluginsPRO as $pluginslug => $pluginlabel)
                {
                        if($this->getActivePlugin() == $pluginslug )
			{
                                $select_option .= "<option value='{$pluginslug}' selected=selected > {$pluginlabel} </option>";
                        }
                        elseif($pluginslug == 'joforce' || $pluginslug == 'wpsuitepro'){
                                $select_option .= "<option value='{$pluginslug}' > {$pluginlabel} </option>" ;            
                        }
                        elseif( in_array( "wp-zoho-crm/index.php" , $active_plugins_hook) && $pluginslug == 'wpzohopro' ){
                                $select_option .= "<option value='wpzohopro' > ZohoCRM </option>" ;
                        }    
                        elseif( in_array( "wp-freshsales/index.php" , $active_plugins_hook)  && $pluginslug == 'freshsales'){
                                $select_option .= "<option value='freshsales' > freshsales </option>" ;
                        }
                        elseif( in_array( "wp-salesforce/index.php" , $active_plugins_hook) && $pluginslug == 'wpsalesforcepro'){
                                $select_option .= "<option value='wpsalesforcepro' > wpsalesforcepro </option>" ;
                        }
                        elseif( in_array( "wp-sugar-free/index.php" , $active_plugins_hook) && $pluginslug == 'wpsugarpro' ){
                                $select_option .= "<option value='wpsugarpro' > wpsugarpro </option>" ;
                        }
                        elseif( in_array( "wp-tiger/index.php" , $active_plugins_hook) && $pluginslug == 'wptigerpro' ){
                                $select_option .= "<option value='wptigerpro' > wptigerpro </option>" ;
                        }
                        else
                        {
                                $select_option .= "<option style='color:gray' value='{$pluginslug}' > {$pluginlabel} </option>" ;
                        }
                }
                $html .= $select_option;
                $html .= "</select></span>";
		return $html;
	}
}
