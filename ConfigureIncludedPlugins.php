<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

global $IncludedPluginsPRO , $DefaultActivePluginPRO , $crmdetailsPRO , $ThirdPartyPlugins , $custom_plugins;
$IncludedPluginsPRO = Array(
	'joforce' => "Joforce",
	'wpsuitepro' =>  "SuiteCRM",
	'wpzohopro' => "ZohoCRM",
	'wpsugarpro' => 'wpsugarpro',
	'wptigerpro' => 'VtigerCRM',
	'freshsales' => 'FreshSales',
	'wpsalesforcepro' => 'SalesForceCRM'
	);

$ThirdPartyPlugins = array('none' => "None",
			   'ninjaform' => "Ninja Forms",
			   'contactform' => "Contact Form",
			   'gravityform' => "Gravity Form" ,
			   'wpform' => "WP Forms" ,
			   'wpformpro' => "WP FORMS PRO"
			);

$WpMappingModule = array(
			'Leads' => 'Leads',
			'Contacts' => 'Contacts',
			);

$custom_plugins = array('none' => "None",
			'wp-members' => "Wp-members",
			'acf' => "ACF" ,
			'acfpro' => "ACF Pro" ,	
			'member-press' => "MemberPress" ,
			'ultimate-member'=> "UltimateMember"
		      ); 

$crmdetailsPRO =array( 
	'joforce'=> array("Label" => "Joforce" , "crmname" => "Joforce" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts") ),
'wptigerpro'=> array("Label" => "WP Tiger" , "crmname" => "VtigerCRM" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts") ),
'wpsugarpro' => array( "Label" => "WP Sugar" , "crmname" => "SugarCRM" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts") ),
'wpsuitepro' => array( "Label" => "WP Suite" , "crmname" => "SuiteCRM" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts") ),
'wpzohopro' => array("Label" => "WP Zoho CRM" , "crmname" => "ZohoCRM" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts")),  
'wpzohopluspro' => array("Label" => "WP Zoho Plus" , "crmname" => "ZohoCRM Plus" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts")),
'wpsalesforcepro' => array("Label" => "WP Salesforce" , "crmname" => "SalesforceCRM" , "modulename" => array("Leads" => "Lead" ,"Contacts" => "Contact") ),
'freshsales'=> array("Label" => "WP FreshSales" , "crmname" => "FreshSales" , "modulename" => array("Leads" => "Leads" ,"Contacts" => "Contacts") )
	);

$DefaultActivePluginPRO = "wpsuitepro";
