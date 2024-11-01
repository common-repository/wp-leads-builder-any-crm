<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

$selectedPlugin= sanitize_text_field($_REQUEST['postdata']);
$active_plugins = get_option("active_plugins");
if( $selectedPlugin != '' ){
	if( $selectedPlugin == "wpzohopluspro" ){
		$selectedPlugin = "wpzohopro";
		$activated = "yes" ;
	}
	switch ($selectedPlugin) {
	case 'wpzohopro':
		if(in_array( "wp-zoho-crm/index.php" , $active_plugins)) {
			update_option('WpLeadBuilderProActivatedPlugin',$selectedPlugin);
			$activated = "yes" ;
		}
		break;
	case 'freshsales':
		if(in_array( "wp-freshsales/index.php" , $active_plugins)) {
			update_option('WpLeadBuilderProActivatedPlugin',$selectedPlugin);
			$activated = "yes" ;
		}

		break;
	case 'wpsalesforcepro':
		if(in_array( "wp-salesforce/index.php" , $active_plugins)) {
			update_option('WpLeadBuilderProActivatedPlugin',$selectedPlugin);
			$activated = "yes" ;
		}

		break;
	case 'wptigerpro':
		if(in_array( "wp-tiger/index.php" , $active_plugins)) {
			update_option('WpLeadBuilderProActivatedPlugin',$selectedPlugin);
			$activated = "yes" ;
		}
		else {
			$activated = "no" ;
		}

		break;
	case 'wpsugarpro':
		if(in_array( "wp-sugar-free/index.php" , $active_plugins)) {
			update_option('WpLeadBuilderProActivatedPlugin',$selectedPlugin);
			$activated = "yes" ;
		}
		break;

	case 'wpsuitepro':
		update_option('WpLeadBuilderProActivatedPlugin',$selectedPlugin);
		$activated = "yes" ;
		break;

	case 'joforce':
		update_option('WpLeadBuilderProActivatedPlugin',$selectedPlugin);
		$activated = "yes" ;
		break;

	 }
	echo esc_attr($activated);die;
}

