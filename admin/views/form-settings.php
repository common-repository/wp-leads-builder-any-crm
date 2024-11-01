<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

$siteurl = site_url();
$siteurl = esc_url( $siteurl );
$config = get_option("wp_thirdpartyplugin_settings");
$active_plugin = get_option('WpLeadBuilderProActivatedPlugin');
$activePlugin = sanitize_text_field($active_plugin);
/* define the plugin folder url */
define('WP_LB_PLUGIN_URL', plugin_dir_url(__FILE__));
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

?>

</script>
<?php
        $Thirdparty_plugin = get_option( "WpLeadThirdPartyPLugin" );  
        $ThirdpartyPlugin = sanitize_text_field($Thirdparty_plugin);          
?>
<input type="hidden" id="third_plugin_value" value='<?php echo esc_attr($ThirdpartyPlugin) ;?>'>
<div>
    <!--  Start -->
    <form id="smack-thirdparty-settings-form" method="post">
        <?php wp_nonce_field('sm-leads-builder'); ?>
        <input type="hidden" name="smack-thirdparty-settings-form" value="smack-thirdparty-settings-form" />
        <input type="hidden" id="plug_URL" value="<?php echo esc_url(SM_LB_URL);?>" />
        <!-- <div class="wp-common-crm-content" style="width: 800px;float: left;">
        </div> -->

        <script>
            jQuery("#dialog-modal").hide();
        </script>
        <span id="Fields" style="margin-right:20px;"></span>
    </form>
    <!-- End-->
</div>

<div id="loading-image"
    style="display: none; background:url(<?php echo esc_url(plugins_url("assets/images/ajax-loaders.gif",dirname(__FILE__,2)));?>) no-repeat center">
    <?php echo esc_html__('' , "wp-leads-builder-any-crm"  ); ?> </div>

<?php

    echo wp_kses('<br>',$allowed_html);   
    $captcha_config = get_option( "wp_captcha_settings" );
    $captcha = SM_LB_URL ;
?>
<div>
    <div class="panel_debug" style="width:99%;background-color: white;">
        <div class="panel-body_debug">

            <div class="captcha" style="margin-left:20px;">
                <form id="smack-<?php echo esc_attr($activePlugin);?>-captcha-form" method="post">
                    <?php wp_nonce_field('sm-leads-builder'); ?>
                    <input type="hidden" name="smack-<?php echo esc_attr($activePlugin);?>-captcha-form"
                        value="smack-<?php echo esc_attr($activePlugin);?>-captcha-form" />

                    <div class="form-group">
                        <center>
                            <h4 id="inneroptions" class="addon_button_heading_debug">
                                <?php echo esc_html__('Debug and Notification Settings' , 'wp-leads-builder-any-crm' );?>
                            </h4>
                        </center>
                    </div>
                    <div class="debug_form">
                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                <label id="innertext"
                                    class="leads-builder-label"><?php echo esc_html__('Which log do you need?' , 'wp-leads-builder-any-crm' );?>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <span id="circlecheck">
                                    <select class="selectpicker form-control" data-live-search="false"
                                        name="emailcondition" id="emailcondition" style="border-color: #2d2626;"
                                        onchange="enablesmackemail(this.id)">
                                        <option value="none" id='smack_email' <?php if(isset($captcha_config['emailcondition']) && $captcha_config['emailcondition'] == 'none'){
                                            echo esc_attr("selected=selected");
                                            }?>>None
                                        </option>
                                        <option value="success" id='successemailcondition' <?php if(isset($captcha_config['emailcondition']) && $captcha_config['emailcondition'] == 'success'){
                                            echo esc_attr("selected=selected");
                                            }?>>Success
                                        </option>
                                        <option value="failure" id='failureemailcondition' disabled <?php if(isset($captcha_config['emailcondition']) && $captcha_config['emailcondition'] == 'failure'){
                                            echo esc_attr("selected=selected");
                                            }?>>Failure
                                        </option>
                                        <option value="both" id='bothemailcondition' disabled <?php if(isset($captcha_config['emailcondition']) && $captcha_config['emailcondition'] == 'both'){ echo esc_attr("selected=selected");
                                             }?>>Both
                                        </option>
                                    </select>
                                </span>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                <label id="innertext" class="leads-builder-label">
                                    <?php echo esc_html__('Specify Your Email', "wp-leads-builder-any-crm" ); ?>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type='text' style="border-radius: 5px;"
                                    class='smack-vtiger-settings form-control' name='email' id='email'
                                    value="<?php if(isset($captcha_config['email'])) { echo esc_attr($captcha_config['email']); } ?>"
                                    <?php if( isset( $captcha_config['emailcondition']) && $captcha_config['emailcondition'] == 'none' ){ ?>
                                    disabled="disabled" <?php } ?> onmouseover="this.style.borderColor='#1caf9a'"
                                    onmouseout="this.style.borderColor='#9b9797'" />
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                <label id="innertext"
                                    class="leads-builder-label"><?php echo esc_html__('Enable Debug mode ' , 'wp-leads-builder-any-crm' ); ?></label>
                            </div>
                            <div class="col-md-4">
                                <div class="switch-ios">
                                    <!-- tfa button -->
                                    <input id="debugmode" type='checkbox'
                                        class="tgl tgl-skewed noicheck smack-vtiger-settings-text" name='debugmode'
                                        <?php if(isset($captcha_config['debugmode']) && sanitize_text_field($captcha_config['debugmode']) == 'on') { echo esc_attr("checked=checked"); } ?>
                                        onclick="debugmod(this.id)" />
                                    <label id="innertext" data-tg-off="OFF" data-tg-on="ON" for="debugmode"
                                        class="tgl-btn" style="font-size: 16px;">
                                    </label>
                                    <!-- tfa btn End -->

                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                <label id="inneroptions"
                                    class="leads-builder-label"><?php echo esc_html__("Do you want to enable the captcha " , "wp-leads-builder-any-crm" ); ?>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <span id="circlecheck">
                                    <input type='radio' name='smack_recaptcha' id='smack_recaptcha_no' value="no"
                                        <?php if(isset($captcha_config['smack_recaptcha']) && $captcha_config['smack_recaptcha']=='no' || !isset($captcha_config['smack_recaptcha'])) { echo esc_attr("checked"); } ?>
                                        onclick="showOrHideRecaptchaPRO('no');">
                                    <label for="smack_recaptcha_no" id="innertext" class="leads-builder-label mr10">
                                        <?php echo esc_html__('No' , 'wp-leads-builder-any-crm' ); ?>
                                    </label>
                                    <input type='radio' name='smack_recaptcha' id='smack_recaptcha_yes' value="yes"
                                        <?php if(isset($captcha_config['smack_recaptcha']) && $captcha_config['smack_recaptcha']=='yes') { echo esc_attr("checked"); } ?>
                                        onclick="showOrHideRecaptchaPRO('yes');">
                                    <label for="smack_recaptcha_yes" id="innertext" class="leads-builder-label">
                                        <?php echo esc_html__('Yes' , 'wp-leads-builder-any-crm' ); ?>
                                    </label>
                                </span>
                            </div>
                        </div>


                        <div class='leads-captcha'>
                            <div id="recaptcha_public_key" <?php 
        if(isset($captcha_config['smack_recaptcha']) && $captcha_config['smack_recaptcha']=='no' || !isset($captcha_config['smack_recaptcha']))
                {
                        $style = 'style="display:none"';
                        echo wp_kses($style,$allowed_html);
                }
                else
                {
                        $style ='style="display:block;margin-top:18px;"';
                        echo wp_kses($style,$allowed_html);
                }?>>
                                <div class="form-group col-md-12">
                                    <div style="margin-left:33%; color:red"> Upgrade to PRO</div>
                                    <div class="col-md-3">
                                        <label id="innertext"
                                            class="leads-builder-label"><?php echo esc_html__('Google Recaptcha Public Key' , 'wp-leads-builder-any-crm' ); ?>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <input type='text' class='smack-vtiger-settings-text form-control'
                                            placeholder='<?php echo esc_attr__('Enter your recaptcha public key here', 'wp-leads-builder-any-crm' ); ?>'
                                            name='recaptcha_public_key' id='smack_public_key' value="" disabled />
                                    </div>
                                </div>

                                <div>
                                    <div style="padding-left:20px; position:relative;top:-9px;">
                                        <a class="tooltip" href="#">
                                            <img
                                                src="<?php echo esc_url(plugins_url('assets/images/help.png',dirname(__FILE__,2))); ?>"><span
                                                class="tooltipPostStatus">
                                                <img src="<?php echo esc_url(plugins_url('assets/images/callout.gif',dirname(__FILE__,2))); ?>"
                                                    class="callout">
                                                <?php echo esc_attr__('Enter your recaptcha public key here.', 'wp-leads-builder-any-crm' ); ?>
                                                <img style="margin-top: 6px;float:right;"
                                                    src="<?php echo esc_url(plugins_url('assets/images/help.png',dirname(__FILE__,2))); ?>">
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div><!-- recaptcha public key div close -->


                            <div id="recaptcha_private_key" <?php
    if(isset($captcha_config['smack_recaptcha']) && $captcha_config['smack_recaptcha']=='no' || !isset($captcha_config['smack_recaptcha']))
    {
        $style ='style="display:none"';
    echo wp_kses($style,$allowed_html);
    }
    else
    {
        $style = 'style="display:block;margin-top:13px"';
        echo wp_kses($style,$allowed_html);
    }
    ?>>
                                <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                        <label id="innertext"
                                            class="leads-builder-label"><?php echo esc_html__("Google Recaptcha Private Key", "wp-leads-builder-any-crm" ); ?></label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type='text' class='smack-vtiger-settings-text form-control'
                                            placeholder='<?php echo esc_attr__("Enter your recaptcha private key here" , "wp-leads-builder-any-crm" ); ?>'
                                            name='recaptcha_private_key' id='smack_private_key' value="" disabled />
                                    </div>
                                </div>

                                <div style="padding-left:14px; position:relative;top:-12px;">
                                    <a class="tooltip" href="#">
                                        <img
                                            src="<?php echo esc_url(plugins_url('assets/images/help.png',dirname(__FILE__,2))); ?>">
                                        <span class="tooltipPostStatus">
                                            <img src="<?php echo esc_url(plugins_url('assets/images/callout.gif',dirname(__FILE__,2))); ?>"
                                                class="callout">
                                            <?php echo esc_attr__("Enter your recaptcha private key here." , "wp-leads-builder-any-crm" ); ?>
                                            <img style="margin-top: 6px;float:right;"
                                                src="<?php echo esc_url(plugins_url('assets/images/help.png',dirname(__FILE__,2))); ?>">
                                        </span> </a>
                                </div>
                            </div><!-- recaptcha private key div close -->
                        </div>
                        <!--leads captcha div close -->
                    </div>


                    <div class="form-group col-md-12">
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-4">

                            <input type="hidden" name="posted" value="<?php echo esc_attr('posted');?>">
                            <input type="button" class="debug_setting_button"
                                value="<?php echo esc_attr__('Save Settings' , 'wp-leads-builder-any-crm' );?>"
                                onclick="save_captcha_key();" id="innersave" />
                        </div>
                    </div>
            </div>
        </div>
    </div>