<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

	$Sync_value = sanitize_text_field( $_REQUEST['syncedvalue'] );
	update_option( 'Sync_value_on_off' , $Sync_value );