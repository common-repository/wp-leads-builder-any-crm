<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * WP Leads Builder For Any CRM plugin file.
 *
 * @package   Smackcoders\LB
 * @copyright Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: WP Leads Builder For Any CRM
 * Version:     3.0.1
 * Plugin URI:  https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html
 * Description: Sync data from Webforms (contact 7 , Ninja & Gravity ) and WP User data to Salesforce, Zoho CRM, Zoho CRM Plus, Vtiger CRM, SuiteCRM, Sugar CRM & Freshsales CRM. Embed forms as Posts, Pages & Widgets.
 * Author:      Smackcoders
 * Author URI:  https://www.smackcoders.com/wordpress.html
 * Text Domain: wp-leads-builder-any-crm
 * Domain Path: /languages
 * License:     GPL v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class SM_WPLeadsBuilderForAnyCRMPro {

	public $version = '3.0.1';

	protected static $_instance = null;


	/**
	 * Main WPLeadsBuilderForAnyCRMPro Instance.
	 *
	 * Ensures only one instance of WPLeadsBuilderForAnyCRMPro is loaded or can be loaded.
	 *
	 * @since 4.5
	 * @static
	 * @return SM_WPLeadsBuilderForAnyCRMPro - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {

		$this->define_constants();
		$this->includes();
		add_action( 'init', array( $this, 'action_crm_init_pro') );
		add_action( 'init', array( $this, 'frontend_init_pro') );
		add_filter('http_request_args', array($this, 'curlArgs'));
		add_filter('safe_style_css', array($this,'style'));
		$this->init();
		$this->init_hooks();
		$active_plugins = get_option( "active_plugins" );
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if( in_array( "contact-form-7/wp-contact-form-7.php" , $active_plugins) )       {
			require_once('templates/contact_form_field_handling.php');
		}

		if( in_array( "wpforms/wp-wpforms.php" , $active_plugins) ||  in_array( "wpforms-lite/wpforms.php" , $active_plugins) )       {
			require_once('templates/wpform_field_handling.php');
		}


		if( in_array( "wpforms/wp-wpforms.php", $active_plugins) || in_array( "wpforms/wpforms.php" , $active_plugins)) {
			require_once( 'templates/wpformpro_form_field_handling.php');
		}

		if(in_array("caldera-forms/caldera-core.php", $active_plugins)){
			require_once('templates/caldera_form_field_handling.php');
		}

		require_once("includes/LBData.php");
		require_once("includes/LBContactFormPlugins.php");

		require_once('includes/WPCapture_includes_helper.php');
		require_once("templates/SmackContactFormGenerator.php");
		require_once('includes/Functions.php');
		
	}

	private function init_hooks() {
		register_activation_hook(__FILE__, array('WPCapture_includes_helper_PRO', 'activate') );
		register_deactivation_hook(__FILE__, array('WPCapture_includes_helper_PRO', 'deactivate') );

		add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array($this, 'lb_plugin_row_meta'), 10, 2 );

		$check_sync_value = get_option( 'Sync_value_on_off' );
		if( $check_sync_value == "On" ){
			add_action( 'user_register', array( 'CapturingProcessClassPRO' , 'capture_registering_users' ) );
		}
		add_action( 'plugins_loaded', array( 'SmackLBAdmin', 'includeFunctions' ), 0 );

	}

	public function define_constants() {
		$this->define( 'SM_LB_PLUGIN_FILE', __FILE__ );
		$this->define( 'SM_LB_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'SM_LB_PRO_DIR', plugin_dir_path(__FILE__));
		$this->define( 'SM_LB_SLUG', 'wp-leads-builder-any-crm' );
		$this->define( 'SM_LB_SETTINGS', 'Leads Builder For Any CRM' );
		$this->define( 'SM_LB_VERSION', '2.1');
		$this->define( 'SM_LB_NAME', 'Leads Builder For Any CRM' );	
		$this->define( 'SM_LB_URL',site_url().'/wp-admin/admin.php?page='.SM_LB_SLUG.'/index.php');
	}

	public function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
	public function style($styles)
	{
		$styles[] = 'display';
		$styles[] = 'opacity';
		$styles[] = 'position';
		return $styles;
	}

	public function init() {
		if(is_admin()) :
			// Init action.
			do_action( 'uci_init' );
		if(is_admin()) {
			include_once('includes/LB_admin_ajax.php');
			SmackLBAdminAjax::smlb_ajax_events();
		}
		endif;
	}

	public function includes() {
		include_once ( 'admin/lb-admin.php' );
		require_once("includes/LBData.php");
		require_once("includes/LBContactFormPlugins.php");
	}


	function action_crm_init_pro()
	{
		$lb_pages_list = array('lb-crmforms' , 'lb-formsettings' , 'lb-usersync' , 'lb-ecominteg','lb-droptable' , 'lb-crmconfig' , 'lb-oppurtunities' , 'lb-customerstats' , 'lb-reports' , 'lb-dashboard' , 'lb-campaign' , 'lb-create-leadform' , 'lb-create-contactform' , 'lb-usermodulemapping' , 'lb-mailsourcing' , 'wp-leads-builder-any-crm','wp-pro-page','wp-hireus-page');
		if ( isset($_REQUEST['page']) && in_array(sanitize_text_field($_REQUEST['page']) , $lb_pages_list) ) {
			wp_enqueue_style('common-crm-free-bootstrap-css', plugins_url('assets/css/bootstrap.css', __FILE__));
			wp_enqueue_style('common-crm-free-bootstrap-min-css', plugins_url('assets/css/bootstrap.min.css', __FILE__));
			wp_enqueue_style('common-crm-free-font-awesome-css', plugins_url('assets/css/font-awesome/css/font-awesome.css', __FILE__));
			wp_enqueue_style('common-crm-free-font-awesome-min-css', plugins_url('assets/css/font-awesome/css/font-awesome.min.css', __FILE__));
			wp_enqueue_style('sweet-alert-css', plugins_url('assets/css/sweetalert.css', __FILE__));
			wp_enqueue_style('main-style', plugins_url('assets/css/mainstyle.css', __FILE__));
			wp_enqueue_script( 'jquery' );
			// Sweet Alert Js
			wp_register_script('sweet-alert-js', plugins_url('assets/js/sweetalert-dev.js', __FILE__));
			wp_enqueue_script('sweet-alert-js');
			wp_enqueue_script( 'notify-js', plugins_url( 'assets/js/notify.js', __FILE__ ) );
			wp_register_script('basic-action-js', plugins_url('assets/js/basicaction.js', __FILE__));
			wp_enqueue_script('basic-action-js');
			wp_register_script('droptabe-js', plugins_url('assets/js/Droptable.js', __FILE__));
			wp_enqueue_script('droptable-js');
			wp_register_script('common-crm-free-bootstrap-min-js', plugins_url('assets/js/bootstrap.min.js', __FILE__));
			wp_enqueue_script('common-crm-free-bootstrap-min-js');
			wp_register_script('boot.min-js', plugins_url('assets/js/bootstrap-modal.min.js', __FILE__));
			wp_enqueue_script('boot.min-js');		
			wp_enqueue_style('leads-builder', plugins_url('assets/css/leads-builder.css', __FILE__));
			wp_enqueue_style('bootstrap-select', plugins_url('assets/css/bootstrap-select.css', __FILE__));
			wp_register_script('bootstrap-select-js', plugins_url('assets/js/bootstrap-select.js', __FILE__));
			wp_enqueue_script('bootstrap-select-js');
			wp_enqueue_style('icheck', plugins_url('assets/css/icheck/green.css', __FILE__));
			wp_enqueue_script( 'icheck-js', plugins_url( 'assets/js/icheck.min.js', __FILE__ ) );
			wp_enqueue_style( 'Icomoon Icons', plugins_url( 'assets/css/icomoon.css', __FILE__ ) );

			/* Create Nonce */
			$secure_uniquekey_leads = array(
				'url' => admin_url('admin-ajax.php') ,
				'nonce' => wp_create_nonce('smack-leads-builder-crm-key')
			);
			wp_localize_script('basic-action-js', 'leads_builder_ajax_object', $secure_uniquekey_leads);
	
		}
	}

	public static function lb_plugin_row_meta( $links, $file ) {
		if ( $file == SM_LB_PLUGIN_BASENAME ) {
			$row_meta = array(
				'upgrade_to_csv_pro' => '<a style="font-weight: bold;color: #d54e21;font-size: 105%;" href="' . esc_url( apply_filters( 'upgrade_to_lb_pro_url',  'https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html?utm_source=lead_builder_free&utm_campaign=plugin_menu&utm_medium=plugin' ) ) . '" title="' . esc_attr( __( 'Upgrade to Pro', 'wp-leads-builder-any-crm' ) ) . '" target="_blank">' . __( 'Upgrade to Pro', 'wp-leads-builder-any-crm' ) . '</a>',
				'settings' => '<a href="' . esc_url( apply_filters( 'sm_lb_settings_url', admin_url() . 'admin.php?page=wp-leads-builder-any-crm' ) ) . '" title="' . esc_attr( __( 'Visit Plugin Settings', 'wp-leads-builder-any-crm' ) ) . '" target="_blank">' . __( 'Settings', 'wp-leads-builder-any-crm' ) . '</a>',
				'docs'     => '<a href="' . esc_url( apply_filters( 'sm_lb_docs_url', 'https://www.smackcoders.com/documentation/leads-builder-for-any-crm-from-wordpress-pro/community-version?utm_source=lead_builder_free&utm_campaign=plugin_menu&utm_medium=plugin' ) ) . '" title="' . esc_attr( __( 'View WP Leads Builder For Any CRM Documentation', 'wp-leads-builder-any-crm' ) ) . '" target="_blank">' . __( 'Docs', 'wp-leads-builder-any-crm' ) . '</a>',
				'videos'   => '<a href="' . esc_url( apply_filters( 'sm_lb_videos_url', 'https://www.youtube.com/watch?v=W8ihpSQhExk' ) ) . '" title="' . esc_attr( __( 'View Videos for WP Leads Builder For Any CRM', 'wp-leads-builder-any-crm' ) ) . '" target="_blank">' . __( 'Videos', 'wp-leads-builder-any-crm' ) . '</a>',
				'support'  => '<a href="' . esc_url( apply_filters( 'sm_lb_support_url', 'https://www.smackcoders.com/support.html?utm_source=lead_builder_free&utm_campaign=plugin_menu&utm_medium=plugin' ) ) . '" title="' . esc_attr( __( 'Contact Support', 'wp-leads-builder-any-crm' ) ) . '" target="_blank">' . __( 'Support', 'wp-leads-builder-any-crm' ) . '</a>',
			);
			unset( $links['edit'] );
			return array_merge( $row_meta, $links );
		}
	}

	public function curlArgs($response) {
		$response['sslverify'] = false;
		return $response;
	}



	public static function frontend_init_pro()
	{
		if(!is_admin())
		{
			include_once ( 'includes/WPCapture_includes_helper.php' );
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('front-end-styles' , plugins_url('assets/css/frontendstyles.css', __FILE__) );
		}
	}
}
function SmackLB() {
	return SM_WPLeadsBuilderForAnyCRMPro::instance();
}
// Global for backwards compatibility.
$GLOBALS['wp_leads_builder_for_any_crm'] = SmackLB();
