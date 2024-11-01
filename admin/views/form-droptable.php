<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
?>

<?php
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
'h2' => ['id' => true, 'align' => true, 'style' => true, 'class' => true]];

    echo wp_kses('<br>',$allowed_html);   
    $droptable_config = get_option( "wp_droptable_settings" );
    // $captcha = SM_LB_URL ;
?>

<div class="panel_drop_table" style="width: 99%;margin-top: 0px;background-color:white" id="all_addons_view" >
    <div class="panel-body_setting">
        <div>
            <center class="drop_table_align"><h4 class="addon_button_heading" style="margin-right:15px">Drop Table Setting</h4>
                <input value="<?php echo esc_attr__('Drop' , 'wp-leads-builder-any-crm' );?>" onclick="drop_table_key();" id="droptable" type='checkbox' class="tgl tgl-skewed noicheck smack-vtiger-settings-text" name='droptable' <?php if(isset($droptable_config['droptable']) && sanitize_text_field($droptable_config['droptable']) == 'on') { echo esc_attr__("checked=checked"); } ?> onclick="droptable(this.id)" />
                <label  id="innertext" data-tg-off="OFF" data-tg-on="ON" for="droptable"  class="tgl-btn" style="font-size: 16px;" >
                </label>        
            </center>
        </div>
        <center><p class="text-muted">*If enabled, plugin deactivation will permanently delete plugin data, which cannot be recovered.</p></center>
    </div>	

</div>

