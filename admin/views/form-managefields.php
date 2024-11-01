<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

$result = '';
global $wpdb,$lb_crmm;
$shortcode = sanitize_text_field($_REQUEST['EditShortcode']);
$activatedplugin = $lb_crmm->getActivatedPlugin(); 

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

$data = $wpdb->get_results("select * from wp_smackleadbulider_shortcode_manager where crm_type = '{$activatedplugin}'");
if( $result !='' ) {
	$result_content ="<div style='font-weight:bold; padding-left:20px; color:red;'> {$result} </div>";
	echo wp_kses($result_content,$allowed_html);
} else {
	$siteurl = site_url();
	$plug_url = SM_LB_URL;
	$field_form_action = add_query_arg( array( '__module' => 'ManageShortcodes' , '__action' => 'ManageFields' , 'crmtype' => sanitize_text_field($_REQUEST['crmtype']) , 'module' => sanitize_text_field($_REQUEST['module']) , 'EditShortcode' => sanitize_text_field($_REQUEST['EditShortcode']) , $plug_url ));
?>
    <form id="field-form" action="<?php echo esc_url("".site_url()."/wp-admin/admin.php?page=lb-create-leadform&__module=ManageShortcodes&__action=ManageFields&onAction=".sanitize_text_field($_REQUEST['onAction'])."&crmtype=".sanitize_text_field($_REQUEST['crmtype'])."&module=".sanitize_text_field($_REQUEST['module'])."&EditShortcode=".$shortcode.""); ?>" method="post">
	<?php wp_nonce_field('sm-leads-builder'); ?>

<?php
	if(isset($shortcode) )
	{
		$crm_type = sanitize_text_field($_REQUEST['crmtype']);
?>
	    <h3 id="innerheader" style="margin-top: 20px;width:98%;border-radius:7px">[<?php echo esc_attr($crm_type);?>-web-form name='<?php echo esc_attr($shortcode);?>']</h3>
<?php
	}
	else
	{

	}
?>

	<div class="wp-common-crm-content" style="background-color: white;width:98%;" >
	    <div class="form-options" style="padding: 20px 0px;">
			<div id="settingsavedmessage" style="height: 42px; display:none; color:red;">   </div>
			<div id="savedetails" style="height: 90px; display:none; color:blue;">   </div>
			<div id="url_post_id" style="display:none; color:blue;">  </div>
			<center><div id="formtext" class="leads-builder-heading mb35"><h4> <?php echo esc_html__('Form Settings' , SM_LB_URL ); ?></h4></div></center>
			<div>
				<div class="form-group col-md-12">
<?php
	$formObj = new CaptureData();

	if(isset($shortcode) && ( $shortcode != "" ))
	{
		$config_fields = $formObj->getFormSettings( $shortcode , $activatedplugin);    // Get form settings
	}

	$content = "";
	$content.= "<div id='innertext' class='col-md-3 leads-builder-label'>".__('Form Type' , SM_LB_URL )."   </div><div class='col-md-3'><select class='selectpicker form-control' data-live-search='false' name='formtype'>";
	$formtypes = array( 'post' => __("Post" , SM_LB_URL ) , 'widget' => __("Widget" , SM_LB_URL ) );
	$select_option = "";
	foreach( $formtypes as $formtype_key => $formtype_value )
	{
		if( $formtype_key == $config_fields->form_type )
		{
			$select_option.= "<option value='{$formtype_key}' selected > {$formtype_value} </option>";
		}
		else
		{
			$select_option.= "<option value='{$formtype_key}'> {$formtype_value} </option>";
		}
	}

	$content.= $select_option;

	$content.= "</select></div>";

	echo wp_kses($content, $allowed_html);
?>
				</div>
			</div>

			<!--dupicate handling  start-->

			<div class="form-group col-md-12">
			<div class="col-md-3">
					<label><div id='innertext' class="leads-builder-label"><?php echo esc_html__("Duplicate handling" , SM_LB_URL ); ?></div></label>
			</div>
			<div class="col-md-8">
					<div class='col-md-2'>
						<span id="circlecheck">
							<label for="smack_capture_duplicates"  id='innertext' class="leads-builder-label">
								<input type='radio'  name='check_duplicate' id='smack_capture_duplicates' value="skip" disabled
<?php if( isset($config_fields->duplicate_handling) && ($config_fields->duplicate_handling == 'skip'))
	{
		echo esc_attr("checked=checked");
	}
?>>
								<?php echo esc_html__("Skip" , SM_LB_URL ); ?>
							</label>
						</span>
					</div>
					<div class='col-md-2'>
						<span id="circlecheck">
							<label for="smack_update_records" id='innertext' class="leads-builder-label">
								<input type='radio'  name='check_duplicate' id='smack_update_records' value= "update" disabled
<?php if(isset($config_fields->duplicate_handling ) && ($config_fields->duplicate_handling == 'update'))
								{
									echo esc_attr("checked=checked");
								}?>>
								<?php echo esc_html__('Update' , SM_LB_URL ); ?>
							</label>
						</span>
					</div>
<?php $activated_crm = get_option( 'WpLeadBuilderProActivatedPlugin' );
if($activated_crm != 'freshsales' || ($activated_crm == 'freshsales' && sanitize_text_field($_REQUEST['module']) != 'Contacts')) { 
?>
				<div class="col-md-2">
						<span id="circlecheck">
							<label for="smack_none_records"  id='innertext' class="leads-builder-label">
							<input type='radio'  name='check_duplicate' id='smack_none_records' value="none"
<?php if(!isset($config_fields->duplicate_handling ) || ( isset($config_fields->duplicate_handling) && ($config_fields->duplicate_handling=='none')))
{
	echo esc_attr("checked=checked");
} 
?>>
								 <?php echo esc_html__("Create" , SM_LB_URL ); ?>
							</label>
						</span>
				</div>
					<?php } ?>
					<!-- Check both Leads, Contacts and Skip -->
					<div class="">
						<span id="circlecheck">
							<label for="smack_capture_duplicates"  id='innertext' class="leads-builder-label">
							<input type='radio'  name='check_duplicate' id='smack_capture_duplicates' value="skip_both" disabled
<?php if( isset($config_fields->duplicate_handling) && ($config_fields->duplicate_handling == 'skip_both'))
								 {
									 echo esc_attr("checked=checked");
								 }
?>>
								 <?php echo esc_html__("Skip if already a Contact or Lead" , SM_LB_SLUG ); ?>
							</label>
						</span>
					</div> <!-- Check Both Leads and Contacts -->
			</div> <!-- radio button div close -->
			</div><!-- form group div close -->

				<!--dupicate handling end -->
				<!-- assign to succcess div start -->
			<div>
			<div class="form-group col-md-12">
				<div class="col-md-3">
					<label id='innertext' class="leads-builder-label">Error Message Submission</label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control" style="border-radius: 7px;border-color:#9b9797" name="errormessage" value="<?php if(isset($config_fields->error_message)) echo esc_attr($config_fields->error_message); ?>" placeholder ="<?php echo esc_html__("Sorry, submission failed" , SM_LB_URL ); ?>" />
				</div>
				<div>
				<div style ="position:relative;top:-9px;">
					<a class="tooltip"  href="#" style="padding-left:8px">
					<img src="<?php echo esc_url(plugins_url('assets/images/help.png',dirname(__FILE__,2))); ?>">
					<span class="tooltipPostStatus">
						<img src="<?php echo esc_url(plugins_url('assets/images/callout.gif',dirname(__FILE__,2)));?>" class="callout">
							<?php echo esc_html__("Message Displayed For Failed Submission." ,SM_LB_URL ); ?>
					</span>
					</a>
				</div>
				</div>
			</div>
			<div class="form-group col-md-12">
				<div class="col-md-3">
					<label id='innertext' class="leads-builder-label"><?php echo esc_html__('Success Message Submission' , 'wp-leads-builder-any-crm' ); ?></label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control" style="border-radius: 7px;border-color:#9b9797" name="successmessage" value="<?php if(isset($config_fields->success_message)) echo esc_attr($config_fields->success_message); ?>" placeholder ="Thanks for Submitting"/>
				</div>
				<div>
					<div style ="position:relative;top:-9px;">
						<a class="tooltip" href="#" style="padding-left:8px">
						<img src="<?php echo esc_url(plugins_url('assets/images/help.png',dirname(__FILE__,2))); ?>">
						<span class="tooltipPostStatus">
							<img src="<?php echo esc_url(plugins_url('assets/images/callout.gif',dirname(__FILE__,2))); ?>" class="callout">
								<?php echo esc_html__('Message Displayed For Successful Submission.' , SM_LB_URL ); ?>
						</span>
						</a>
					</div>
				</div>
			</div>
			</div>
			<!-- assign to succcess div close -->

			<!-- label fields close div -->

			<div>
			<div class="form-group col-md-12">
					<div class="col-md-3">
					<label id='innertext' class="leads-builder-label"><?php echo esc_html__('Enable URL Redirection' , SM_LB_URL ); ?> </label>
					</div>
					<div class="col-md-2">
					<div class="switch">
							<!-- tfa button -->
							<input id="enableurlredirection" type='checkbox' class="tgl tgl-skewed noicheck" name='enableurlredirection' onclick="enableredirecturl(this.id);" value="on" <?php if(isset($config_fields->is_redirection) && ($config_fields->is_redirection == '1')){ echo esc_attr("checked=checked"); } ?> />
							<label  data-tg-off="OFF" data-tg-on="ON" for="enableurlredirection"  class="tgl-btn" style="font-size: 16px;" >
							</label>
							<!-- tfa btn End -->
					</div>
					</div>
					<div class="col-md-2">
					<input class="form-control" id="redirecturl" style="border-radius: 7px;border-color:#9b9797" type="text" name="redirecturl" <?php if(!isset($config_fields->is_redirection) == '1'){ echo esc_attr("disabled=disabled");} ?> value="<?php if(isset($config_fields->url_redirection)) echo esc_url($config_fields->url_redirection); ?>" placeholder = "<?php echo esc_attr__('Page id or Post id' , SM_LB_URL ); ?>"/>
					</div>
					<div style="padding-left:10px;">
					<div style ="position:relative;top:-9px;">
							<a class="tooltip" href="#">
							<img src="<?php echo esc_url(plugins_url('assets/images/help.png',dirname(__FILE__,2))); ?>">
							<span class="tooltipPostStatus">
								<img src="<?php echo esc_url(plugins_url('assets/images/callout.gif',dirname(__FILE__,2))); ?>" class="callout">
									<?php echo esc_html__("(Give your custom success page url post id to redirect leads)." , SM_LB_URL ); ?>
							</span>
							</a>
					</div>

					</div>
			</div>


<?php
$thirdparty_form = get_option( 'Thirdparty_'.$shortcode);
$thirdparty_title_key = $shortcode;
$check_thirdparty_title = get_option( $thirdparty_title_key );
?>

				<div>
					<div class="col-md-offset-9">
<?php $check_thirparty_val_exist = get_option( 'Thirdparty_'.$shortcode );
$thirdparty_option_available = 'no';
if( $check_thirparty_val_exist != '')
{
	$thirdparty_option_available = 'yes';
}
?>
						<input type="hidden" name='thirdparty_option_available' id='thirdparty_option_available' value="<?php echo esc_attr($thirdparty_option_available);?>">
						<input style="margin-left: -90px;margin-top: -50px;" class="save_form_button" type="button" onclick="saveFormSettings('<?php echo esc_attr($shortcode); ?>');" value="<?php echo esc_attr__("Save Form Settings" , SM_LB_URL ); ?>" name="SaveFormSettings" />
					</div>
				</div>
			</div>
			<span style="padding:10px; color:#00a699; background-color: #FFFFFF; text-align:center; float:right; font-weight:bold; cursor:pointer;margin-right:2%;margin-top: -35px;" id ="showless"><?php echo esc_html__("Form Options" , SM_LB_URL ); ?> <i class="dashicons dashicons-arrow-up"></i></span>
			<!-- label fields close div -->

	    </div>
	</div>

	<span style="padding:10px; color:#FFFFFF; background-color: #00a699; text-align:center; float:right; font-weight:bold; cursor:pointer;margin-right:2%;margin-top: -54px;" id ="showmore"><?php echo esc_html__("Form Options" , SM_LB_URL ); ?> <i class="dashicons dashicons-arrow-down"></i></span>
	<br>

	<div>
	    <div class="panel_form" style="width:98%;background-color:white">
			<div class="panel-body_form">
				<div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class='mb30'>
							<center><div id="formtext" class="leads-builder-heading"><h4> <?php echo esc_html__('Form Field Settings' , SM_LB_URL ); ?></h4> </div></center>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-12" style="margin-bottom:10px">
						<div class="col-md-4">

							<?php
							global $crmdetailsPRO;
							$content = "";
							if(isset($shortcode)) {
								$content .= "<b><span id='inneroptions' class='leads-builder-sub-heading'>CRM Type  :";
								$content .= "<span> </b>";
								foreach( $crmdetailsPRO as $crm_key => $crm_value ){
									if(isset($_REQUEST['crmtype']) && ($crm_key == sanitize_text_field($_REQUEST['crmtype']))) {
										$select_option = " {$crm_value['crmname']} ";
									}
								}
								$content .= $select_option;
								$content .= "</span>";
								$content .= "</span>";

								echo wp_kses($content, $allowed_html);
							}
							else {
								$module = sanitize_text_field($_REQUEST['module']);
								$content.= "<span id='inneroptions'>CRM Type  : <select id='crmtype' name='crmtype' style='margin-left:8px;height:27px;' class=''
								onchange = \"SelectFieldsPRO('{$siteurl}','{$module}','{smack_fields_shortcodes}', '{onEditShortcode}')\">";
								$select_option = "";
								$select_option .= "<option> --".__('Select' , SM_LB_URL )."-- </option>";
								foreach( $crmdetailsPRO as $crm_key => $crm_value )
								{
									if(isset($_REQUEST['crmtype']) && ($crm_key == sanitize_text_field($_REQUEST['crmtype']))){
										$select_option.= "<option value='{$crm_key}' selected=selected > {$crm_value['crmname']} </option>";
									} else {
										$select_option.= "<option value='{$crm_key}'> {$crm_value['crmname']} </option>";
									}
								}
								$content.= $select_option;
								$content.= "</select></span>";
								echo wp_kses($content, $allowed_html);
							}
							?>
							<?php
							global $crmdetailsPRO;
							global $DefaultActivePluginPRO;

							$content = "";
							if(isset($shortcode)) {
								$content .= "<span id='inneroptions' class='leads-builder-sub-heading' style='position:relative;left:40px;'><b>Module Type  :</b> ";
								$content .= "<span> ";
								foreach( $crmdetailsPRO[sanitize_text_field($_REQUEST['crmtype'])]['modulename'] as $key => $value )
								{
									if(isset($_REQUEST['module']) && (sanitize_text_field($_REQUEST['module']) == $key ) ){
										$select_option = " {$value} ";
									}
								}
								$content .= $select_option;
								$content .= "</span>";
								$content .= "</span>";
								echo wp_kses($content, $allowed_html);
							}
							else
							{
							$module = sanitize_text_field($_REQUEST['module']);
							$content.= "<span id='inneroptions' style='position:relative;left:40px;'>Module Type  : <select id='module' name='module' style='margin-left:8px;height:27px;' onchange = \"SelectFieldsPRO('{$siteurl}','{$module}','{smack_fields_shortcodes}', '{onEditShortcode}')\" >";
							$select_option = "";
							$select_option .= "<option> --".__('Select' , SM_LB_URL )."-- </option>";
							foreach( $crmdetailsPRO[sanitize_text_field($_REQUEST['crmtype'])]['modulename'] as $key => $value)
							{
								if(isset($_REQUEST['module']) && (sanitize_text_field($_REQUEST['module']) == $key ) )
								{
									$select_option.= "<option value = '{$key}' selected=selected  > {$value}</option>";
								}
								else
								{
									$select_option.= "<option value = '{$key}' > {$value}</option>";
								}
							}
							$content.= $select_option;

							$content.= "</select></span>";
							echo wp_kses($content, $allowed_html);
							}
							?>
						</div>
							<div class="col-md-4"></div>
							<div class="form-group col-md-4"style="padding-left:10px" >
								<div class="col-md-12"style="padding-left:10px">
									<div class="col-md-8"style="padding-left:10px">
										<select id="bulk-action-selector-top" class="selectpicker form-control" name="bulkaction">
											<option selected="selected" value="-1"><?php echo __('Bulk Actions' , SM_LB_URL ); ?></option>
											<option value="enable_field"><?php echo esc_attr__('Enable Field' , SM_LB_URL ); ?></option>
											<option value="disable_field"><?php echo esc_attr__('Disable Field' , SM_LB_URL ); ?></option>
											<option value="enable_mandatory" disabled><?php echo esc_attr__('Enable Mandatory' , SM_LB_URL ); ?></option>
											<option value="disable_mandatory" disabled><?php echo esc_attr__('Disable Mandatory' , SM_LB_URL ); ?></option>
											<option value="save_field_label_display" disabled><?php echo esc_attr__('Save Display Label' , SM_LB_URL ); ?></option>
										</select>
									</div>
									<div>
										<input type='hidden' id='lead_crmtype' name="lead_crmtype" value="<?php echo esc_attr(get_option('WpLeadBuilderProActivatedPlugin'));?>">
										<input type="hidden" id="savefields" name="savefields" value="<?php echo esc_attr__('Apply' , SM_LB_URL ); ?>"/>
											<?php if(isset($shortcode))
											{
												$module = sanitize_text_field($_REQUEST['module']);
												$editshortcode =sanitize_text_field($_REQUEST['EditShortcode']);
												$onaction = sanitize_text_field($_REQUEST['onAction']);
												$content = "";
												$content.= "<input class='save_apply_button' id='generate_forms' type='button' value='".__("Apply" , SM_LB_URL )."' onclick =  \" return SaveCheckPRO('".site_url()."','{$module}','smack_fields_shortcodes','{$editshortcode}', '{$onaction}')\" />";
												echo wp_kses($content, $allowed_html);
											}
											?>
									</div>
								</div>
							</div>
					</div>
						<div class="form-group"></div>

	<script>
	jQuery(document).ready(function($) {
		$( ".form-options" ).hide();
		$( "#showless" ).hide();

		$( "#showmore" ).click(function() {
			$( ".form-options" ).show( 600 );
			$( "#showless" ).show();
			$( "#showmore" ).hide();
			$(".form-options").css('overflow', 'visible');
		});

		$( "#showless" ).click(function() {
			$( ".form-options" ).hide( 600 );
			$( "#showless" ).hide();
			$( "#showmore" ).show();
		});

	});
	</script>
						<div id="fieldtable">
	<?php
	require_once( SM_LB_PRO_DIR ."includes/class_lb_manage_shortcodes.php" );
	$FieldOperations = new FieldOperations();
	if(isset($shortcode)){
		$short = $FieldOperations->formFields( "smack_fields_shortcodes" , sanitize_text_field($_REQUEST['onAction']) , $shortcode , 'post' );
		echo wp_kses($short,$allowed_html);
	}
	else{
		$short = $FieldOperations->formFields( "smack_fields_shortcodes" , sanitize_text_field($_REQUEST['onAction']) , '' , 'post' );
		echo wp_kses($short,$allowed_html);
	}

	?>
						</div>
				</div>
			</div>
		</div>
	<!-- </div> -->
<script>
function showAccordion( id )
{
	if(jQuery("#advance_option_display").val() == 0) {
		jQuery("#advance_option").css("display", "block");
		jQuery("#advance_option_display").val(1);
		jQuery("#accordion_arrow").removeClass( "fa-chevron-right" );
		jQuery("#accordion_arrow").addClass( "fa-chevron-down" );
	} else {
		jQuery("#advance_option").css("display", "none");
		jQuery("#advance_option_display").val(0);
		jQuery("#accordion_arrow").removeClass( "fa-chevron-down" );
		jQuery("#accordion_arrow").addClass( "fa-chevron-right" );
	}
}
</script>
	    <br>
    </form>

    <div id="loading-image" style="display: none; background:url(<?php echo esc_url(plugins_url('assets/images/ajax-loaders.gif',dirname(__FILE__,2)));?>) no-repeat center"><?php echo esc_html__('' , SM_LB_URL ); ?></div>
<?php
}
