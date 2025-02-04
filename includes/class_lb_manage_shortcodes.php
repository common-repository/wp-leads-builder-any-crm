<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class FieldOperations
{
	public $nonceKey = null;
	public function __construct() {
		require_once(SM_LB_PRO_DIR.'includes/Functions.php');
	}

	function formFields( $options, $onAction, $editShortCodes , $formtype = "post" )
	{
		$CaptureData = new CaptureData();
		$content1='';
		$config_leads_fields = $CaptureData->formfields_settings( $editShortCodes );
		$imagepath = plugins_url('assets/images/',dirname(__FILE__));
		$imagepath = esc_url( $imagepath );
		$content='
		<input type="hidden" name="field-form-hidden" value="field-form" />
		<div>';
		$i = 0;
		if(!isset($config_leads_fields['fields'][0]))
		{
			$content.='<p style="color:red;font-size:20px;text-align:center;margin-top:-22px;margin-bottom:20px;">'.__("Crm fields are not yet synchronised", "wp-leads-builder-any-crm" ).' </p>';
		}
		else
		{
			$content.='<form method="post" name = "userform" id="userform" action="'.plugins_url("/includes/class-lb-manage-shortcodes.php",dirname(__FILE__)).'">
				<table class="table" style="border: 1px solid #dddddd;width:100%;margin-bottom:26px;margin-top:0px" id="sort_table">
				<thead>
				<tr class="smack_highlight smack_alt lb-table-heading" style="border-bottom: 1px solid #dddddd;">
				<th style="width: 2%;"></th>
			    <th class="smack-field-td-middleit" align="left" style="width: 8%;">
			    <input type="checkbox" name="selectall" id="selectall" style="margin-top:-3px"/>
			    </th>
			    <th style="width: 30%;" align="left"><h5>'.__('Field Name', 'wp-leads-builder-any-crm' ).'</h5>
			    </th>
			    <th style="width: 14%;" class="smack-field-td-middleit" align="left"><h5>'.__('Show Field', 'wp-leads-builder-any-crm' ).'</h5>
			    </th>
			    <th style="width: 14%;" class="smack-field-td-middleit" align="left"><h5>'.__('Mandatory' , 'wp-leads-builder-any-crm' ).'</h5>
			    </th>
			    <th style="width: 30%;" class="smack-field-td-middleit" align="left" style="width:20%;"><h5>'.__('Field Label Display' , 'wp-leads-builder-any-crm' ).'</h5>
			    </th>
			    </tr></thead><tbody>';

				wp_nonce_field('sm-leads-builder');
				$count=count($config_leads_fields['fields']);
			for($i=0; $i < $count; $i++)
			{
				// if( $config_leads_fields['fields'][$i]['wp_mandatory'] == 1 ) {
					// $madantory_checked = "checked";
				// } else {
				// 	$madantory_checked = "";
				// }
				$field_id = $config_leads_fields['fields'][$i]['field_id'];
				if( isset($config_leads_fields['fields'][$i]['mandatory']) && $config_leads_fields['fields'][$i]['mandatory'] == 2) {
					if($i % 2 == 1)
						$content1.='<tr class="smack_highlight smack_alt">';
					else
						$content1.='<tr class="smack_highlight">';

					$content1.='<td style="width: 2%; float: right;" class="sortable">::</td>';
					$content1.='
					<td style="width: 8%;" class="smack-field-td-middleit tdsort"><input type="checkbox" class="pos_checkbx" name="select'.$field_id.'" id="select'.$i.'" disabled=disabled checked=checked ></td>
					<td style="width: 30%;">'.$config_leads_fields['fields'][$i]['label'].' *</td>
					<td style="width: 14%;" class="smack-field-td-middleit">';
					$content1.='<a name="publish'.$field_id.'" id="publish'.$i.'" onclick="'."alert('".__('This field is mandatory, cannot hide' , 'wp-leads-builder-any-crm' )."')".'"> <img src="'.$imagepath.'tick_strict.png"/></a></td>';
					$content1 .='<td style="width: 14%;" class="smack-field-td-middleit"><input type="checkbox" name="mandatory'.$field_id.'" id="mandatory'.$i.'" disabled=disabled checked=checked ></td>';
					$content1.='<td style="width: 30%;" class="smack-field-td-middleit"><input type="text" class="form-control" name="fieldlabel'.$field_id.'"  id="field_label_display_'.$i.'" value="'.$config_leads_fields['fields'][$i]['display_label'].'"></td>
					</tr>';
				}
				else
				{
					if($i % 2 == 1)
						$content1.='<tr class="smack_highlight smack_alt">';
					else
						$content1.='<tr class="smack_highlight">';
					$content1.='<td style="width: 2%; float: right;" class="sortable">::</td>';
					$content1.='<td style="width: 8%;" class="smack-field-td-middleit tdsort">';
					if($config_leads_fields['fields'][$i]['publish'] == 1){
						$content1.= '<input type="checkbox" name="select'.$field_id.'" id="select'.$i.'" class="pos_checkbx">';
					} else {
						$content1.= '<input type="checkbox" name="select'.$field_id.'" id="select'.$i.'" class="pos_checkbx">';
					}
					$content1.='</td>
					<td style="width: 30%;">'.$config_leads_fields['fields'][$i]['label'].'</td>
					<td style="width: 14%;" class="smack-field-td-middleit">';
					if($config_leads_fields['fields'][$i]['publish'] == 1 || $config_leads_fields['fields'][$i]['publish'] == '') {
						$content1.='<p name="publish'.$field_id.'" id="publish'.$i.'" ><span class="is_show_widget" style="color: #019E5A;">Yes</span></p>';
					} else {
						$content1.='<p name="publish'.$field_id.'" id="publish'.$i.'" ><span class="not_show_widget" style="color: #FF0000;">No</span></p>';
					}
					$content1.='</td>';
					$content1.=' <td style="width: 14%;" class="smack-field-td-middleit">';
					if($config_leads_fields['fields'][$i]["wp_mandatory"] == 1 || $config_leads_fields['fields'][$i]["wp_mandatory"] == '') {
						$content1 .= '<p name="mandatory'.$field_id.'" id="mandatory'.$i.'" >
						<span class="is_show_widget" style="color: #019E5A;">'.__("Yes", "wp-leads-builder-any-crm" ).'</span>
						</p>';
					} else {
						$content1 .= '<p name="mandatory'.$field_id.'" id="mandatory'.$i.'" >
						<span class="not_show_widget" style="color: #FF0000;">'.__("No", "wp-leads-builder-any-crm" ).'</span>
						</p>';
					}
					$content1 .= '</td>';
					$content1.='<td style="width: 30%;" class="smack-field-td-middleit" ><input type="text"  class="form-control" id="field_label_display_'.$i.'" name="fieldlabel'.$field_id.'" value="'.$config_leads_fields['fields'][$i]['display_label'].'"></td>
					</tr>';
				}
			}
			$content1.="<input type='hidden' name='no_of_rows' id='no_of_rows' value={$i} />";
			$content.=$content1;
			$content.= '</tbody></table>
		</form>';
		}
		?>
		<script>
		jQuery(document ).ready(function(){
			jQuery("tbody").sortable({
				update: function( event, ui ) {
					var orderArray = new Array;
					var siteurl = "<?php echo esc_url(site_url()); ?>";
					var module = '<?php echo esc_attr($_REQUEST['module']); ?>';
					var option = 'smack_fields_shortcodes';
					var shortcode = '<?php echo esc_attr($_REQUEST['EditShortcode']); ?>';
					var onAction = '<?php echo esc_attr($_REQUEST['onAction']); ?>';
					var crmtype = document.getElementById("lead_crmtype").value;
					var bulkaction = 'Update Order';
					var chkarray = [];
					var labelarray = [];
					jQuery("#sort_table").find('tr').each(function (i, el) {
						if( i != 0){
							var tds = jQuery(this).find('td.tdsort');
							var idx = tds.eq(0).find('input').attr('id');
							var namex = tds.eq(0).find('input').attr('name');
							var get_pos = idx.split("select");
							var get_field_id = namex.split("select");
							orderArray.push(parseInt(get_field_id[1]));
						}
					});
					var orderarray = JSON.stringify(orderArray);
					var flag = true;
					jQuery.ajax({
						type: 'POST',
						url: leads_builder_ajax_object.url,
						data: {
							'action'     : 'adminAllActionsPRO',
							'doaction'   : 'CheckformExits',
							'siteurl'    : siteurl,
							'module'     : module,
							'crmtype'    : crmtype,
							'option'     : option,
							'onAction'   : onAction,
							'shortcode'  : shortcode,
							'bulkaction' : bulkaction,
							'chkarray'   : chkarray,
							'labelarray' : labelarray,
							'orderarray' : orderarray,
							'securekey'   : leads_builder_ajax_object.nonce,
						},
						success:function(data) {
							document.getElementById('loading-image').style.display = "none";
							if(data == "Not synced") {
								alert("Must Fetch fields before Saving Settings");
								flag = false;
								return false;
							} else {
								swal('Success', 'Field order updated successfully!', 'success');
							}
						},
						error: function(errorThrown){
						}
					});
					return flag;
				}
			});
		});
		jQuery('tbody').sortable({
			handle: '.handle'
		});
		</script>	
		<?php
		return $content;
	}

	function enableFields( $selectedfields , $shortcode_name )
	{
		global $wpdb;
		$string2 = "";

		if( isset( $selectedfields ) ) {
			foreach($selectedfields as $field_id)
			{
				$enable_showfields = $wpdb->get_results($wpdb->prepare("select ffm.form_field_sequence, ffm.rel_id, sm.shortcode_id from wp_smackleadbulider_form_field_manager as ffm inner join wp_smackleadbulider_shortcode_manager as sm on ffm.shortcode_id = sm.shortcode_id where ffm.field_id = %d and sm.shortcode_name = %s order by ffm.form_field_sequence", $field_id, $shortcode_name));
				$string2 .= "'" . $enable_showfields[0]->rel_id . "',";
			}
		}
		$trim2 = rtrim($string2, ',');
		$wps_enablefields = $enable_showfields[0]->shortcode_id;
		$wpdb->query("update wp_smackleadbulider_form_field_manager set state = '1' where rel_id in ($trim2) and shortcode_id = '$wps_enablefields'");
	}

	function disableFields( $selectedfields, $shortcode_name )
	{
		global $wpdb;
		$string3 = "";
		if( isset( $selectedfields ) ) {
			foreach($selectedfields as $field_id)
			{
				$disable_showfields = $wpdb->get_results($wpdb->prepare("select ffm.form_field_sequence, ffm.rel_id, sm.shortcode_id from wp_smackleadbulider_form_field_manager as ffm inner join wp_smackleadbulider_shortcode_manager as sm on ffm.shortcode_id = sm.shortcode_id where ffm.field_id = %d and sm.shortcode_name = %s order by ffm.form_field_sequence", $field_id, $shortcode_name));
				$string3 .= "'" . $disable_showfields[0]->rel_id . "',";
			}
		}
		$trim3 = rtrim($string3, ',');
		$wps_disablefields = $disable_showfields[0]->shortcode_id;
		$wpdb->query("update wp_smackleadbulider_form_field_manager set state = '0' where rel_id in ($trim3) and shortcode_id = '$wps_disablefields'");
	}

	function updateFieldsOrder( $field_order, $shortcode_name )
	{
		$field_order = array_flip($field_order);
		global $wpdb;
		$crm_type = sanitize_text_field($_REQUEST['crmtype']);
		$get_shortcode_id = $wpdb->get_results($wpdb->prepare("select shortcode_id from wp_smackleadbulider_shortcode_manager where shortcode_name = %s and crm_type = %s", $shortcode_name, $crm_type));
		$shortcode_id = $get_shortcode_id[0]->shortcode_id;

		$get_existing_field_order = $wpdb->get_results($wpdb->prepare("select field_id, rel_id, form_field_sequence from wp_smackleadbulider_form_field_manager where shortcode_id = %d order by form_field_sequence", $shortcode_id));
		foreach($get_existing_field_order as $key => $ffOrder) {
			$wpdb->update( 'wp_smackleadbulider_form_field_manager',
				array( 'form_field_sequence' => $field_order[$ffOrder->field_id] ),
				array( 'rel_id' => $ffOrder->rel_id )
			);
		}
	}
}

class ManageShortcodesActions {

	public $nonceKey = null;
	public function __construct()
	{
		require_once(SM_LB_PRO_DIR.'includes/Functions.php');
	}

	/**
	 * The actions index method
	 * @param array $request
	 * @return array
	 */

	public function executeIndex($request)
	{
		// return an array of name value pairs to send data to the template
		$data = array();
		return $data;
	}


	public function executeManageFields1($request)
	{
		$data = $request;
		return $data;
	}
	public function ManageFields($shortcode, $crmtype, $module, $bulkaction, $chkArray, $newlabelarray, $orderArray)
	{
		$FieldOperation = new FieldOperations();
		$CaptureData = new CaptureData();
		$new_field_positions=[];
		$config_leads_fields = $CaptureData->formfields_settings( $shortcode );
		if( isset( $bulkaction ) )
		{
			$selectedfields = array();
			if(!empty($config_leads_fields['fields'])) {
				foreach ( $config_leads_fields['fields'] as $index => $fInfo ) {
					$current_field_positions[ $fInfo['field_id'] ] = $fInfo['order'];
				}
			}
			if(!empty($chkArray)) {
				foreach ( $chkArray as $key => $value ) {
					$selectedfields[] = $value;
				}
			}
			if(!empty($orderArray)) {
				foreach ( $orderArray as $key1 => $value1 ) {
					#$new_field_positions[$current_field_positions[$key1]] = $value1;
					#$new_field_positions[$value1] = $current_field_positions[$value1];
					$new_field_positions[ $key1 + 1 ] = $value1; #$current_field_positions[]
					// $current_field_positions[$key1 + 1];
				}
			}
			if(!empty($newlabelarray)) {
				foreach ( $newlabelarray as $key2 => $value2 ) {
					$fieldLabelDisplay[] = $value2;
				}
			}
			$bulkaction = isset($bulkaction) ? $bulkaction : 'enable_field';
			$shortcode_name = $shortcode;
			switch( $bulkaction ) {
				case 'Enable Field':
					$FieldOperation->enableFields( $selectedfields , $shortcode_name );
					break;
				case 'Disable Field':
					$FieldOperation->disableFields( $selectedfields , $shortcode_name );
					break;
				case 'Update Order':
					$FieldOperation->updateFieldsOrder($new_field_positions, $shortcode_name);
					break;
			}
		}
		//Action 1
		//check the selected Third party plugin
		
		$get_edit_shortcode = $shortcode;
		$thirdPartyPlugin = get_option('Thirdparty_'.$shortcode);
		$get_thirdparty_title = get_option( $get_edit_shortcode );
		
		if($thirdPartyPlugin == 'contactform' ) {
			$title = $crmtype . '-'.$module.'-'.$shortcode;
			$obj = new CallManageShortcodesCrmObj();
		}

		//Action 2
		if($thirdPartyPlugin == 'contactform' ) {
			$get_edit_shortcode = $shortcode;
 	               $get_thirdparty_title = get_option( $get_edit_shortcode );
	
			if( !empty($get_thirdparty_title )) {
				$title = $get_thirdparty_title;
			} else {
				$title = $get_edit_shortcode;
			}	
			$obj->formatContactFields($thirdPartyPlugin, $title, $shortcode);
		}
		if($thirdPartyPlugin == 'wpform' ) {
			$title = $crmtype . '-'.$module.'-'.$shortcode;
			$obj = new CallManageShortcodesCrmObj();
		}

		if($thirdPartyPlugin == 'wpformpro' ) {
			$title = $crmtype . '-'.$module.'-'.$shortcode;
			$obj = new CallManageShortcodesCrmObj();
		}

		if($thirdPartyPlugin == 'wpform' ) {
			$get_edit_shortcode = $shortcode;
 	               $get_thirdparty_title = get_option( $get_edit_shortcode );
	
			if( !empty($get_thirdparty_title )) {
				$title = $get_thirdparty_title;
			} else {
				$title = $get_edit_shortcode;
			}	
			$obj->formatContactFields($thirdPartyPlugin, $title, $shortcode);
		}

		if($thirdPartyPlugin == 'wpformpro' ) {
			$get_edit_shortcode = $shortcode;
 	               $get_thirdparty_title = get_option( $get_edit_shortcode );
	
			if( !empty($get_thirdparty_title )) {
				$title = $get_thirdparty_title;
			} else {
				$title = $get_edit_shortcode;
			}	
			$obj->formatContactFields($thirdPartyPlugin, $title, $shortcode);
		}
		
		$data = array();
		return $data;
	}

	public static function CreateShortcode($module)
	{
		global $lb_crmm;
		$shortcode_details =[];
		$data=[];
		$data['HelperObj'] = new WPCapture_includes_helper_PRO();
		$crmtype = $data["HelperObj"]->ActivatedPlugin;
		$moduleslug = rtrim( strtolower($module) , "s");
		$tmp_option = "smack_{$crmtype}_{$moduleslug}_fields-tmp";
		// Function call
		$shortcodeObj = new CaptureData();
		$OverallFunctions = new OverallFunctionsPRO();
		$randomstring = $OverallFunctions->CreateNewFieldShortcode( $crmtype , $module );
		$is_redirection = '';
		$url_redirection = '';
		$google_captcha = '';
		$config_fields['crm'] = $crmtype;
		$users_list = get_option('crm_users');
		$assignee = $users_list[$crmtype]['id'][0];
		$shortcode_details['name'] = $randomstring;
		$shortcode_details['type'] = 'post';
		$shortcode_details['assignto'] = $assignee;
		$shortcode_details['isredirection'] = $is_redirection;
		$shortcode_details['urlredirection'] = $url_redirection;
		$shortcode_details['captcha'] = $google_captcha;
		$shortcode_details['crm_type'] = $crmtype;
		$shortcode_details['module'] = $module;
		$shortcode_details['errormesg'] = '';
		$shortcode_details['successmesg'] = '';
		$shortcode_details['duplicate_handling'] = '';
		$lb_crmm->setShortcodeDetails($shortcode_details);
		$shortcode_id = $shortcodeObj->formShorcodeManager($shortcode_details);
		$config_fields = $shortcodeObj->get_crmfields_by_settings($crmtype, $module);
		foreach( $config_fields as $field )
		{
			$shortcodeObj->insertFormFieldManager( $shortcode_id , $field->field_id , $field->field_mandatory , '1' , $field->field_type, $field->field_values , $field->field_sequence, $field->field_label );
		}
		$details =array();
		$details['shortcode'] = $randomstring;
		$details['module'] = $module;
		$details['crmtype'] =  $crmtype;
		return $details;
	}

	public function DeleteShortcode($shortcode)
	{
		global $wpdb;
		$delete_short = $shortcode;
		$deletedata = $wpdb->get_results("select shortcode_id from wp_smackleadbulider_shortcode_manager where shortcode_name = '$delete_short'");
		$deleteid = $deletedata[0]->shortcode_id;
		$wpdb->query("delete from wp_smackleadbulider_shortcode_manager where shortcode_id = '$deleteid'");
		$wpdb->query( "delete from wp_smackleadbulider_form_field_manager where shortcode_id = '$deleteid'" );
		return $deletedata;
		exit;
	}
}

class CallManageShortcodesCrmObj extends ManageShortcodesActions
{
	private static $_instance = null;
	public static function getInstance()
	{
		if( !is_object(self::$_instance) ) 
			self::$_instance = new CallManageShortcodesCrmObj();
		return self::$_instance;
	}

	public function formatContactFields($thirdparty_form,$title,$shortcode){
		global $wpdb;
		$word_form_enable_fields = $wpdb->get_results("select a.rel_id,a.wp_field_mandatory,a.custom_field_type,a.custom_field_values,a.display_label from wp_smackleadbulider_form_field_manager as a join wp_smackleadbulider_shortcode_manager as b where b.shortcode_id=a.shortcode_id and b.shortcode_name='{$shortcode}' and a.state=1 order by form_field_sequence");
		$checkid = $wpdb->get_var( $wpdb->prepare( "select thirdpartyid from wp_smackformrelation where shortcode =%s and thirdparty=%s" , $shortcode , 'contactform' ) );
		$checkid = $wpdb->get_var( $wpdb->prepare( "select thirdpartyid from wp_smackformrelation where shortcode =%s and thirdparty=%s" , $shortcode , 'wpform'  ) );
		$checkid = $wpdb->get_var( $wpdb->prepare( "select thirdpartyid from wp_smackformrelation where shortcode =%s and thirdparty=%s" , $shortcode , 'wpformpro'  ) );

		if(!empty($checkid))
		{
			$wpdb->query( $wpdb->prepare( "delete from wp_smackthirdpartyformfieldrelation where thirdpartyformid=%d" , $checkid ) );
		}
		$contact_array = '';
		foreach($word_form_enable_fields as $key=>$value) {
			$type = $value->custom_field_type;
			$labl = $value->display_label;
			$label = preg_replace('/[^a-zA-Z]+/','_',$labl);
			$label = ltrim($label,'_');
			$mandatory = $value->wp_field_mandatory;
			$cont_array = array();
			$cont_array = unserialize($value->custom_field_values);
			$string ="";
			if( !empty( $cont_array ) )
			{
				foreach($cont_array as $val) {
					$string .= "\"{$val['label']}\" ";
				}
			}
			$str = rtrim($string,',');
			if($mandatory == 0)
			{
				$man ="";
			}
			else
			{
				$man ="*";
			}
			switch($type)
			{
				case 'phone':
				case 'currency':
				case 'text':
				case 'integer':
				case 'string':
					$contact_array .= "<p>".  $label ."".$man. "<br />[text".$man." ".  $label."] </p>" ;
					break;
				case 'email':
					$contact_array .= "<p>".  $label ."".$man. "<br />[email".$man." ". $label."] </p>" ;
					break;
				case 'url':
					$contact_array .= "<p>".  $label ."".$man. "<br />[url".$man." ". $label."] </p>" ;
					break;
				case 'picklist':
					$contact_array .= "<p>".  $label ."".$man. "<br />[select".$man." ". $label." " .$str."] </p>" ;
					$str ="";
					break;
				case 'boolean':
					$contact_array .= "<p>[checkbox".$man." ". $label." "."label_first "."\" $label\""."] </p>" ;
					break;
				case 'date':
					$contact_array .= "<p>".  $label ."".$man. "<br />[date".$man." ". $label." min:1950-01-01 max:2050-12-31 placeholder \"YYYY-MM-DD\"] </p>" ;
					break;
				case '':
					$contact_array .= "<p>".  $label ."".$man. "<br />[text".$man." ".  $label."] </p>" ;
					break;
				default:
					break;
			}
		}
		$contact_array .= "<p><br /> [submit "." \"Submit\""."]</p>";
		$meta = $contact_array;
		$checkid = $wpdb->get_var( $wpdb->prepare( "select thirdpartyid from wp_smackformrelation inner join {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = wp_smackformrelation.thirdpartyid and {$wpdb->prefix}posts.post_status='publish' where shortcode =%s and thirdparty=%s" , $shortcode , 'contactform'  ) );
		$checkid = $wpdb->get_var( $wpdb->prepare( "select thirdpartyid from wp_smackformrelation inner join {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = wp_smackformrelation.thirdpartyid and {$wpdb->prefix}posts.post_status='publish' where shortcode =%s and thirdparty=%s" , $shortcode , 'wpform'  ) );
		$checkid = $wpdb->get_var( $wpdb->prepare( "select thirdpartyid from wp_smackformrelation inner join {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = wp_smackformrelation.thirdpartyid and {$wpdb->prefix}posts.post_status='publish' where shortcode =%s and thirdparty=%s" , $shortcode , 'wpformpro'  ) );

		if(empty($checkid))
		{
			$contform = array (
					'post_title'  => $title,
					'post_content'=> $contact_array,
					'post_type'   => 'wpcf7_contact_form',
					'post_status' => 'publish',
					'post_name'   => $shortcode
			);
			$id = wp_insert_post($contform);
			$content2 = "[contact-form-7 id=\"$id\" title=\"$shortcode\"]";
			$contform2 = array (
					'post_title'  => $id,
					'post_content'=> $content2,
					'post_type'   => 'post',
					'post_status' => 'publish',
					'post_name'   => $id
			);
			wp_insert_post($contform2);

			$post_id = $id;
			$meta_key ='_form';
			$meta_value = $meta;
			update_post_meta($post_id,$meta_key,$meta_value);
			$wpdb->query( "update wp_smackformrelation set thirdpartyid = {$id} where thirdparty='contactform' and shortcode ='{$shortcode}'" );
		}
		else
		{
			$wpdb->update( $wpdb->posts , array( 'post_content' => $contact_array , 'post_title' => $title ) , array( 'ID' => $checkid ) );
			$wpdb->update( $wpdb->postmeta , array( 'meta_value' => $meta ) , array( 'post_id' => $checkid , 'meta_key' => '_form'));
			$id = $checkid;
		}
		$thirdPartyPlugin = $thirdparty_form;
		$obj = new CallManageShortcodesCrmObj();
		$obj->contactFormRelation($shortcode,$id,$thirdPartyPlugin,$word_form_enable_fields);
	}

	public function contactFormRelation($shortcode,$id,$thirdparty,$enablefields)
	{
		global $wpdb;
		//TODO update tables
		$checkid = $wpdb->get_var( $wpdb->prepare( "select thirdpartyid from wp_smackformrelation where shortcode =%s" , $shortcode ) );
		if(empty($checkid))
		{
			$wpdb->insert( 'wp_smackformrelation' , array( 'shortcode' => $shortcode, 'thirdparty' => $thirdparty , 'thirdpartyid' => $id ) );
		}
		foreach($enablefields as $value)
		{
			$labl = $value->display_label;
			$labid = preg_replace('/[^a-zA-Z]+/','_',$labl);
			$labid = ltrim($labid,'_');
			$wpdb->insert( 'wp_smackthirdpartyformfieldrelation' , array( 'smackshortcodename' => $shortcode , 'smackfieldid' => $value->rel_id , 'smackfieldslable' => $value->display_label , 'thirdpartypluginname' => $thirdparty , 'thirdpartyformid' => $id , 'thirdpartyfieldids' => $labid ) );
		}
	}
}
