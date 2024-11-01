<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

function checkActiveOrNot($plugin)
{
	include_once(ABSPATH.'wp-admin/includes/plugin.php');
	$active_plugins_hook = get_option( "active_plugins" );
	$mode=[];
	switch ($plugin) {
	case 'vtiger':
		$hook = 'wp-tiger/index.php';
		$mode['link'] = 'https://wordpress.org/plugins/wp-tiger/';
		break;
	case 'zoho':
		$hook = 'wp-zoho-crm/index.php';
		$mode['link'] = 'https://wordpress.org/plugins/wp-zoho-crm/';
		break; 
	case 'sforce':
		$hook = 'wp-salesforce/index.php';
		$mode['link'] = 'https://wordpress.org/plugins/wp-salesforce/';
		break;
	case 'fsales':
		$hook = 'wp-freshsales/index.php';
		$mode['link'] = 'https://wordpress.org/plugins/wp-freshsales/';
		break;
	case 'sugar':
		$hook = 'wp-sugar-free/index.php';
		$mode['link'] = 'https://wordpress.org/plugins/wp-sugar-free/';
		break;
	}

	$dir = ABSPATH.'wp-content/plugins/'.$hook;
	if(is_file($dir)){
		$mode['text'] = 'activate addon';
	}
	else{
		$mode['text'] = 'get addon';
	}

	if(in_array($hook, $active_plugins_hook)){
		$mode['disable'] = 'disabled';
		$mode['text'] = 'active';
	}
	else{
		$mode['disable'] = '';
	}

	return $mode;
}

$vt_btn = checkActiveOrNot('vtiger');
$zoho_btn = checkActiveOrNot('zoho');
$sforce_btn = checkActiveOrNot('sforce');
$fsales_btn = checkActiveOrNot('fsales');
$sugar_btn = checkActiveOrNot('sugar');

function migrate_leadbuild_addon($link) {

	$allowed_html = ['div' => ['class' => true, 'id' => true, 'style' => true, ], 
	'a' => ['id' => true, 'href' => true, 'title' => true, 'target' => true, 'class' => true, 'style' => true, 'onclick' => true,], 
	'strong' => [], 
	'i' => ['id' => true, 'onclick' => true, 'style' => true, 'class' => true, 'aria-hidden' => true, 'title' => true ], 
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

	require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
	$plugin=[];
	$plugin['source'] = $link;
	$source = ( isset($type) && 'upload' == $type ) ? $this->default_path . $plugin['source'] : $plugin['source'];
	/** Create a new instance of Plugin_Upgrader */
	$upgrader = new Plugin_Upgrader( $skin = new Plugin_Installer_Skin( compact( 'type', 'title', 'url', 'nonce', 'plugin', 'api' ) ) );
	/** Perform the action and install the plugin from the $source urldecode() */
	$upgrader->install( $source );
	/** Flush plugins cache so we can make sure that the installed plugins list is always up to date */
	wp_cache_flush();
	$plugin_activate = $upgrader->plugin_info(); // Grab the plugin info from the Plugin_Upgrader method
	$activate = activate_plugin( $plugin_activate ); // Activate the plugin
	if ( !is_wp_error( $activate ) )
		if ( is_wp_error( $activate ) ) {
			$content = '<div id="message" class="error"><p>' . $activate->get_error_message() . '</p></div>';
			echo wp_kses($content,$allowed_html);
			return true; // End it here if there is an error with automatic activation
		}
		else {

		}
	die();

}
$allowed_html = ['div' => ['class' => true, 'id' => true, 'style' => true, ], 
	'a' => ['id' => true, 'href' => true, 'title' => true, 'target' => true, 'class' => true, 'style' => true, 'onclick' => true,], 
	'strong' => [], 
	'i' => ['id' => true, 'onclick' => true, 'style' => true, 'class' => true, 'aria-hidden' => true,'title' => true ], 
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
	'option' => ['value' => true, 'selected' => true],
	'label' =>['id' => true, 'class' =>true],
	'input' => ['type' => true, 'value' => true, 'id' => true, 'name' => true, 'class' => true, 'onclick' => true],
	'form' => ['method' => true, 'name' => true, 'id' => true, 'action' => true]];

if(isset($_POST) && isset($_POST['addon_to_install'])){
	$addon = sanitize_text_field($_POST['addon_to_install']);
	switch ($addon) {
	case 'vtiger':
		$link = 'https://downloads.wordpress.org/plugin/wp-tiger.3.4.zip';
		$hook = 'wp-tiger/index.php';
		break;
	case 'zoho':
		$link = 'https://downloads.wordpress.org/plugin/wp-zoho-crm.1.4.zip';
		$hook = 'wp-zoho-crm/index.php';
		break; 
	case 'sforce':
		$link = 'https://downloads.wordpress.org/plugin/wp-salesforce.1.0.zip';
		$hook = 'wp-salesforce/index.php';
		break;
	case 'fsales':
		$link = 'https://downloads.wordpress.org/plugin/wp-freshsales.1.0.zip';
		$hook = 'wp-freshsales/index.php';
		break;
	case 'sugar':
		$link = 'https://downloads.wordpress.org/plugin/wp-sugar-free.1.4.zip';
		$hook = 'wp-sugar-free/index.php';
		break;
	}

	$dir = ABSPATH.'wp-content/plugins/'.$hook;
	if(is_file($dir)){
		include_once(ABSPATH.'wp-admin/includes/plugin.php');
		$activate = activate_plugin($hook);
		if ( !is_wp_error( $activate ) ){
			echo wp_kses("<script>location.reload();</script>",$allowed_html);
		}
	}else{
?> 
<div class="panel" style="width: 99%;" >
<div class="panel-body">
	<h4>Installing.... </h4>
	<hr>
	<div style="height: auto;min-height: 200px">
 </div>
 </div>
 </div>
<?php
		die();

	}
}
?>
<div class="panel" id="all_addons_view" >
<div class="panel-body">
	<center><h4 class="addon_button_heading">More Addons are here, Choose one that Aligns with your Requirements</h4></center>
	<div class="addon_button_div">
		<div class="addon_button">
			<a target="blank" href="<?php echo esc_url($vt_btn['link']); ?>" class="addon_button_anchor">
				<!-- <img src="<?php //echo SM_LB_DIR?>assets/images/vtiger-logo.png" style="height: 42px; padding-top: 10px"> -->
				Vtiger
			</a>
		</div>
	</div>
	<div class="addon_button_div">
		<div class="addon_button">
				<a target="blank" href="<?php echo esc_url($zoho_btn['link']); ?>" class="addon_button_anchor">
					<!-- <img src="<?php //echo SM_LB_DIR?>assets/images/vtiger-logo.png" style="height: 42px; padding-top: 10px"> -->
					Zoho
				</a>
			</div>
		</div>
	<div class="addon_button_div">
		<div class="addon_button">
				<a target="blank" href="<?php echo esc_url($sugar_btn['link']); ?>" class="addon_button_anchor">
					<!-- <img src="<?php //echo SM_LB_DIR?>assets/images/vtiger-logo.png" style="height: 42px; padding-top: 10px"> -->
					Sugar
				</a>
			</div>
		</div>
	<div class="addon_button_div">
		<div class="addon_button">
			<a target="blank" href="<?php echo esc_url($sforce_btn['link']); ?>" class="addon_button_anchor">
				<!-- <img src="<?php //echo SM_LB_DIR?>assets/images/vtiger-logo.png" style="height: 42px; padding-top: 10px"> -->
				Salesforce
			</a>
		</div>
	</div>
	<div class="addon_button_div">
		<div class="addon_button">
			<a target="blank" href="<?php echo esc_url($fsales_btn['link']); ?>" class="addon_button_anchor">
				<!-- <img src="<?php //echo SM_LB_DIR?>assets/images/vtiger-logo.png" style="height: 42px; padding-top: 10px"> -->
				Freshsales
			</a>
		</div>
	</div>

</div>	

</div>