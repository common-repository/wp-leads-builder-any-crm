<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

require_once( SM_LB_PRO_DIR."includes/Functions.php" );
$OverallFunctionsPROObj = new OverallFunctionsPRO();
$result = $OverallFunctionsPROObj->CheckFetchedDetails();
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
if( !$result['status'] )
{
	$display_content = "<br>".$result['content']. " to configure WP User Sync <br><br>" ;
	$content = "<div style='font-weight:bold;  color:red;font-size:16px;text-align:center'> $display_content </div>";
        echo wp_kses($content,$allowed_html);
}
else
{
$active_plugin = get_option('WpLeadBuilderProActivatedPlugin');

if( isset($data['display']) )
{
	echo esc_attr($data['display']);
}
$config = get_option("smack_{$active_plugin}_user_capture_settings");
?>
<div class='clearfix'></div>
<div class='mt40'>
	<div class='panel_form_setting' style='width:99%;background-color: white;'>
		<div class='panel-body'>
			<?php 
	$custom_plugin = get_option( "custom_plugin" );
?>
			<input type="hidden" id="custom_plugin_value" value='<?php echo esc_attr($custom_plugin);?>'>
			<form id="smack-<?php echo esc_attr($active_plugin);?>-user-capture-settings-form" action="" method="post">
				<?php wp_nonce_field('sm-leads-builder'); ?>
				<input type="hidden" name="smack-<?php echo  esc_attr($active_plugin);?>-user-capture-settings-form"
					value="smack-<?php echo esc_attr($active_plugin);?>-user-capture-settings-form" />

				<input type="hidden" name="activated_crm" id="activated_crm"
					value="<?php echo esc_attr($active_plugin); ?>">

				<div class='leads-builder-heading col-md-12 '>
					<center>
						<h4 class="user_heading">
							<?php echo esc_html__("Capture WordPress Users" , "wp-leads-builder-any-crm" ); ?></h4>
					</center>
				</div>
				<div class='clearfix'></div>
				<div class='mt20'>
					<div class="debug_form">
						<div class="form-group col-md-12">
							<div class="col-md-4">
								<label id="innertext"
									class='leads-builder-label'><?php echo esc_html__( 'Select Plugin-Custom Fields ' , 'wp-leads-builder-any-crm' ); ?></label>
							</div>
							<div class="col-md-3">
								<?php $ContactFormPluginsObj = new ContactFormPROPlugins();
             echo wp_kses($ContactFormPluginsObj->getCustomFieldPlugins(),$allowed_html);?>
							</div>
							<div style="margin-left:60%;color:red;font-size:16px">
								<a href ="https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html" target="_blank"><h6 style="color:red"><?php echo esc_html__("Upgrade To Pro*");?></h6></a>
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="col-md-4">
								<label id="innertext"
									class='leads-builder-label'><?php echo esc_html__("Sync Wordpress User as" , SM_LB_URL ); ?>
								</label>
							</div>
							<div class="col-md-3">
								<select name="choose_module" id="choose_module" class='selectpicker form-control'
									data-live-search='false' onchange="wpSyncSettingsPRO( this )">
									<option value="Leads" <?php
					if( isset( $config['user_sync_module']  ) && $config['user_sync_module'] == "Leads" )
                                        {
                                                echo esc_attr("selected=selected");
                                        }
				?>> Leads </option>
								</select>
							</div>

							<div class="col-md-2">
								<?php
						if(!empty($config['user_sync_module'])){
							$module_name = rtrim( $config['user_sync_module'] , "s" ) ;
						}
                        ?>

								<input type="button"
									value="<?php echo esc_attr__('Configure Mapping' , 'wp-leads-builder-any-crm' );?>"
									class="config_mapping_button"
									onClick="window.location.href='<?php echo esc_url(site_url()."/wp-admin/admin.php?page=lb-usermodulemapping"); ?>'" />
							</div>

						</div>


						<div class="form-group col-md-12">
							<div class="col-md-4">
								<label id="innertext"
									class='leads-builder-label'><?php echo esc_html__("On Duplicate Data" , "wp-leads-builder-any-crm" ); ?></label>
							</div>
							<div class="col-md-3">
								<select name="duplicate_handling" id="duplicate_handling"
									class='selectpicker form-control col-md-2'
									onchange="wpSyncDuplicateSettingsPRO( this )">
									<option value="skip" disabled <?php
				if( isset( $config['smack_capture_duplicates'] ) && $config['smack_capture_duplicates'] == "skip"  ) 
				{
					echo esc_attr("selected=selected");
				}
				?>> Skip </option>
									<option value="skip_both" disabled <?php
                                if( isset( $config['smack_capture_duplicates'] ) && $config['smack_capture_duplicates'] == "skip_both"  )
                                {
                                        echo esc_attr("selected=selected");
                                }
                                ?>> Skip if already a Contact or Lead</option>
									<option value="update" disabled <?php
                                if( isset( $config['smack_capture_duplicates'] ) && $config['smack_capture_duplicates'] == "update"  ) 
                                {
                                        echo esc_attr("selected=selected");
                                }
                                ?>> Update </option>
									<?php 
				$activated_crm = get_option( 'WpLeadBuilderProActivatedPlugin' );
		                if($activated_crm != 'freshsales' || ($activated_crm == 'freshsales' && $config['user_sync_module'] != "Contacts")) { ?>
									<option value="create" <?php
                                if( isset( $config['smack_capture_duplicates'] ) && $config['smack_capture_duplicates'] == "create"  ) 
                                {
                                        echo esc_attr("selected=selected");
                                }
                                ?>> Create </option>
									<?php } ?>
								</select>
							</div>
							<div style="margin-left:60%;color:red;font-size:16px">
								<a href ="https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html" target="_blank"><h6 style="color:red"><?php echo esc_html__("Upgrade To Pro*");?></h6></a>
							</div>
						</div>

						<div>
							<!-- wp user auto sync div start -->
							<input type="hidden" name="posted" value="<?php echo esc_attr('posted');?>"> <br>
							<div class="form-group col-md-12">
								<div class="col-md-4">
									<label id="innertext"
										class='leads-builder-label'><?php echo esc_html__("WP User Auto Sync" , "wp-leads-builder-any-crm" ); ?></label>
								</div>
								<div class='col-md-3'>
									<input type="button" id="OneTimeSync"
										class="OTMS smack-btn smack-btn-primary btn-radius"
										<?php if( $switch_sync_button == "On" ) { echo esc_attr("disabled"); } ?>
										value="<?php echo esc_attr__('One Time Manual Sync ', 'wp-leads-builder-any-crm' );?>"
										class="button-secondary submit-add-to-menu innersave" disabled />
										<span class="label label-warning" style="position: absolute;margin-left:68%"> Pro </span>	
									</td>
								</div>
							</div>
						</div> <!-- wp-user auto syn div close -->
						<div>
							<!-- leads owner div start -->

							<?php
//Assign Leads And Contacts to User

$crm_users_list = get_option( 'crm_users' );
$assignedtouser_config = get_option( "smack_{$activated_crm}_usersync_assignedto_settings" );
if(isset($assignedtouser_config['usersync_assign_contacts'])) {
$assignedtouser_config_leads = $assignedtouser_config['usersync_assign_leads'];
}
else{
	$assignedtouser_config_leads = "";	
}
if(isset($assignedtouser_config['usersync_assign_contacts'])) {
$assignedtouser_config_contacts = $assignedtouser_config['usersync_assign_contacts'];
} else {
$assignedtouser_config_contacts = ""; }
$Assigned_users_list = $crm_users_list[$activated_crm];
switch( $activated_crm )
{
	case 'wpzohopro':
	case 'wpzohopluspro':
		$html_leads = "";
		$html_leads = '<select class="selectpicker form-control" name="usersync_assignedto_leads" id="usersync_assignedto_leads">';
		$content_option_leads = "";
		$content_option_leads = "<option id='select' value='--Select--'>--Select--</option>";
		if(isset($Assigned_users_list['user_name']))
			for($i = 0; $i < count($Assigned_users_list['user_name']) ; $i++)
			{
				$content_option_leads.="<option id='{$Assigned_users_list['user_name'][$i]}' value='{$Assigned_users_list['id'][$i]}'";
				if($Assigned_users_list['id'][$i] == $assignedtouser_config_leads )
				{
					$content_option_leads .=" selected";
				}
				$content_option_leads .=">{$Assigned_users_list['user_name'][$i]}</option>";
			}
		$content_option_leads .= "<option id='rr_usersync_owner' value='Round Robin' disabled";
		if( $assignedtouser_config_leads == 'Round Robin' )
		{
			$content_option_leads .= "selected";
		}
		$content_option_leads .= "> Round Robin</option>";
		$html_leads .= $content_option_leads;
		$html_leads .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
		break;
	case 'joforce':
			$html_leads = "";
			$html_leads = '<select class="form-control" name="usersync_assignedto_leads" id="usersync_assignedto_leads">';
			$content_option_leads = "";
			$content_option_leads = "<option id='select' value='--Select--'>--Select--</option>";
			if(isset($Assigned_users_list['user_name']))
				for($i = 0; $i < count($Assigned_users_list['user_name']) ; $i++)
				{
					$content_option_leads .="<option id='{$Assigned_users_list['id'][$i]}' value='{$Assigned_users_list['id'][$i]}'";
					if($Assigned_users_list['id'][$i] == $assignedtouser_config_leads)
					{
						$content_option_leads .=" selected";
					}
					$content_option_leads .=">{$Assigned_users_list['first_name'][$i]} {$Assigned_users_list['last_name'][$i]}</option>";
				}
			$content_option_leads .= "<option id='rr_usersync_owner' value='Round Robin'";
			if( $assignedtouser_config_leads == 'Round Robin' )
			{
				$content_option_leads .= "selected";
			}
			$content_option_leads .= "> Round Robin</option>";
			$html_leads .= $content_option_leads;
			$html_leads .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
			break;  
	case 'wptigerpro':
		$html_leads = "";
		$html_leads = '<select class="selectpicker form-control" name="usersync_assignedto_leads" id="usersync_assignedto_leads">';
		$content_option_leads = "";
		$content_option_leads = "<option id='select' value='--Select--'>--Select--</option>";
		if(isset($Assigned_users_list['user_name']))
			for($i = 0; $i < count($Assigned_users_list['user_name']) ; $i++)
			{
				$content_option_leads .="<option id='{$Assigned_users_list['id'][$i]}' value='{$Assigned_users_list['id'][$i]}'";
				if($Assigned_users_list['id'][$i] == $assignedtouser_config_leads)
				{
					$content_option_leads .=" selected";
				}
				$content_option_leads .=">{$Assigned_users_list['first_name'][$i]} {$Assigned_users_list['last_name'][$i]}</option>";
			}
		$content_option_leads .= "<option id='rr_usersync_owner' value='Round Robin' disabled";
		if( $assignedtouser_config_leads == 'Round Robin' )
		{
			$content_option_leads .= "selected";
		}
		$content_option_leads .= "> Round Robin</option>";
		$html_leads .= $content_option_leads;
		$html_leads .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
		break;
	case 'wpsugarpro':
	case 'wpsuitepro':
		$html_leads = "";
		$html_leads = '<select class="selectpicker form-control" name="usersync_assignedto_leads" id="usersync_assignedto_leads">';
		$content_option_leads = "";
		$content_option_leads = "<option id='select' value='--Select--'>--Select--</option>";
		if(isset($Assigned_users_list['user_name']))
			for($i = 0; $i < count($Assigned_users_list['user_name']) ; $i++)
			{
				$content_option_leads .="<option id='{$Assigned_users_list['id'][$i]}' value='{$Assigned_users_list['id'][$i]}'";
				if($Assigned_users_list['id'][$i] == $assignedtouser_config_leads)
				{
					$content_option_leads .=" selected";
				}
				$content_option_leads .=">{$Assigned_users_list['first_name'][$i]} {$Assigned_users_list['last_name'][$i]}</option>";
			}
		$content_option_leads .= "<option id='rr_usersync_owner' value='Round Robin'";
		if( $assignedtouser_config_leads == 'Round Robin' )
		{
			$content_option_leads .= "selected";
		}
		$content_option_leads .= "> Round Robin</option>";
		$html_leads .= $content_option_leads;
		$html_leads .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
		break;
	case 'wpsalesforcepro':
		$html_leads = "";
		$html_leads = '<select class="selectpicker form-control" name="usersync_assignedto_leads" id="usersync_assignedto_leads" >';
		$content_option_leads = "";
		$content_option_leads = "<option id='select' value='--Select--'>--Select--</option>";
		if(isset($Assigned_users_list['user_name']))
			for($i = 0; $i < count($Assigned_users_list['user_name']) ; $i++)
			{
				$content_option_leads .="<option id='{$Assigned_users_list['user_name'][$i]}' value='{$Assigned_users_list['id'][$i]}'";
				if($Assigned_users_list['id'][$i]== $assignedtouser_config_leads)
				{
					$content_option_leads .=" selected";
				}
				$content_option_leads .=">{$Assigned_users_list['user_name'][$i]}</option>";
			}
		$content_option_leads .= "<option id='rr_usersync_owner' value='Round Robin'";
		if( $assignedtouser_config_leads == 'Round Robin' )
		{
			$content_option_leads .= "selected";
		}
		$content_option_leads .= "> Round Robin</option>";
		$html_leads .= $content_option_leads ;
		$html_leads .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
		break;
	case 'freshsales':
		$html_leads = "";
		$html_leads = '<select class="selectpicker form-control" name="usersync_assignedto_leads" id="usersync_assignedto_leads" >';
		$content_option_leads = "";
		$content_option_leads = "<option id='select' value='--Select--'>--Select--</option>";
		if(isset($Assigned_users_list['last_name']))
			for($i = 0; $i < count($Assigned_users_list['last_name']) ; $i++)
			{
				$content_option_leads .="<option id='{$Assigned_users_list['last_name'][$i]}' value='{$Assigned_users_list['id'][$i]}'";
				if($Assigned_users_list['id'][$i]== $assignedtouser_config_leads)
				{
					$content_option_leads .=" selected";
				}
				$content_option_leads .=">{$Assigned_users_list['last_name'][$i]}</option>";
			}
		$content_option_leads .= "<option id='rr_usersync_owner' value='Round Robin'";
		if( $assignedtouser_config_leads == 'Round Robin' )
		{
			$content_option_leads .= "selected";
		}
		$content_option_leads .= "> Round Robin</option>";
		$html_leads .= $content_option_leads ;
		$html_leads .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
		break;
}
?>

							<div class="form-group col-md-12">
								<div class="col-md-4">
									<div id='Lead_owner' style="width:250px;">
										<label id="innertext"
											class='leads-builder-label'><?php echo esc_html__("Lead Owner" , "wp-leads-builder-any-crm" ); ?>
										</label>
									</div>
									<div id='Contact_owner' style="width:250px;">
										<label id="innertext"
											class='leads-builder-label'><?php echo esc_html__("Contact Owner" , "wp-leads-builder-any-crm" ); ?>
										</label>
									</div>
								</div>
								<div class="col-md-3">
									<?php echo wp_kses($html_leads,$allowed_html); ?>
								</div>
							</div>

						</div><!-- leads owner div close -->
					</div>

					<div>
						<!-- wp-user note div start -->
					</div>
					<!--hole label div close -->
			</form>
			<div id="loading-image"
				style="display: none; background:url(<?php echo esc_url(plugins_url('assets/images/ajax-loaders.gif',dirname(__FILE__,2)));?>) no-repeat center">
				<?php echo esc_html__('' , 'wp-leads-builder-any-crm' ); ?></div>
		</div>
	</div>
</div>
<?php
}