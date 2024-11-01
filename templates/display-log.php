<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly
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
$content1='';
$content='
	<input type="hidden" name="field-form-hidden" value="field-form" />
	<div>';
	$i=0;
	if(!isset($config_fields['fields'][0]))
	{
		$content.='<p style="color:red;font-size:20px;text-align:center;">Crm fields are not yet synchronised</p>';
	}
	else
	{
		$content .='<div id="fieldtable">';
		$content.='<table style="font-size:15px;border: 1px solid #dddddd;width:24%;margin-bottom:26px;margin-left:54%;margin-top:10px"><tr class="smack_highlight smack_alt" style="border-bottom: 1px solid #dddddd;"><th align="left" style="width: 200px;"><h5>Synced Fields:</h5></th></tr>';
		for($i=0;$i<count($config_fields['fields']);$i++)
		{
			$content1.= '<tr>
				<td>'.$config_fields['fields'][$i]['label'].'</td>
				<td class="smack-field-td-middleit"></td>
			</tr>';
		}
		$content1.="<input type='hidden' name='no_of_rows' id='no_of_rows' value={$i} />";
		$content1.= "</table></div>";
	}
		$content.=$content1;
$content .='</div>';
echo wp_kses($content,$allowed_html);