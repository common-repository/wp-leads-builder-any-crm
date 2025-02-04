//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
jQuery(document).ready( function(){
	
	jQuery( ".mapping-modalbox" ).hide();	
	jQuery("#thirdparty_map").click(function(){
		jQuery("#mapping-modalbox").show();
		jQuery( "#clear_contents" ).show();
		});
	jQuery(".modal_open_use_exist").click(function(){
		jQuery(".alert_modal_view").css("display","block")
		jQuery(".alert_modal_config").css("display","none")
	})	
	jQuery(".modal_open_exist").click(function(){
		jQuery(".alert_modal_config").css("display","block")
		jQuery(".alert_modal_view").css("display","none")
	})	
});

jQuery(document).ready(function(){
	jQuery( ".import-modalbox" ).hide();
	jQuery("#import_file").click(function(){
		document.getElementById("myfile-button").disabled = true;
		jQuery("#import-modalbox").show();
		jQuery("#clear_contents").show();
	});
});

jQuery(document).ready( function(){

		jQuery(".selectpicker").selectpicker('refresh');
		jQuery(".bootstrap-select").click(function () {
			jQuery(this).toggleClass("open");
		});
		jQuery(".selectpicker").on('changed.bs.select',function(){
			jQuery(".selectpicker").selectpicker('refresh');
			jQuery(this).addClass("active");
		});
		jQuery(".bs-searchbox").css("width","89%","height","40px");

		jQuery(function(){
				jQuery('.RegField_iCheck, input[type=radio]:not(".noicheck"), input[type=checkbox]:not(".noicheck")')
				.iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
				});

				});

Notify = function(text, callback, close_callback, style) {

	var time = '5000';
	var $container = jQuery('#notifications');
	var icon = '<i class="fa fa-info-circle "></i>';

	if (typeof style == 'undefined' ) style = 'warning'

		var html = jQuery('<div class="alert alert-' + style + '  hide">' + icon +  " " + text + '</div>');  
	$container.prepend(html)
		html.removeClass('hide').hide().fadeIn('fast')
		function remove_notice() {
			html.stop().fadeOut('slow').remove()
		}
	var timer =  setInterval(remove_notice, time);
	jQuery(html).hover(function(){
			clearInterval(timer);
			}, function(){
			timer = setInterval(remove_notice, time);
			});

	html.on('click', function () {
			clearInterval(timer)
			callback && callback()
			remove_notice()
			});
}

//Select All icheck
jQuery('#selectall')
	.on('ifChecked', function(event) {
			var chkBx_count = jQuery("#no_of_rows").val();
			var i, a = 0;
			for (i=0; i < chkBx_count; i++){
			if(document.getElementById('select'+i).disabled == false)
			jQuery('#select'+i).iCheck('check');
			} 
			})
	.on('ifUnchecked', function() {
			var chkBx_count = jQuery("#no_of_rows").val();
			var i, a=0;
			for (i=0; i < chkBx_count; i++){
			if(document.getElementById('select'+i).disabled == false)
			jQuery('#select'+i).iCheck('uncheck');
			}
			});
//End Select All icheck

//Set Active tab
	var url = window.location.href;
	jQuery.urlParam = function(name){
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
		return results[1] || 0;
	}
	var activeModule = jQuery.urlParam('page');
	switch( activeModule ){
		case 'wp-leads-builder-any-crm':
			case 'lb-crmconfig':
				jQuery('#menu5').addClass("nav-tab-lb-active");
				break;
			case 'lb-droptable':
				jQuery('#menu6').addClass("nav-tab-lb-active");
				break;
			case 'lb-import':
				jQuery('#menu7').addClass("nav-tab-lb-active");
				break;
		case 'lb-crmforms':
			case 'lb-create-leadform':
				jQuery('#menu1').addClass("nav-tab-lb-active");
				break;

			case 'lb-formsettings':
				jQuery('#menu2').addClass("nav-tab-lb-active");
				break; 

		case 'lb-usersync':
			case 'lb-usermodulemapping':
				jQuery('#menu3').addClass("nav-tab-lb-active");
				break;

		case 'lb-ecominteg':
			jQuery('#menu4').addClass("nav-tab-lb-active");
			break;

		case 'default':
			break;
	}
//Set Active tab end here

//ECOM MODULE CHANGE
	var module_own = jQuery("#ecom_module").val();
	if( module_own == 'Not Enabled' )
	{
		jQuery( "#choose_owner" ).hide();
		jQuery( "#load_ecom_fields" ).hide();
		jQuery( "#hide_convert" ).hide();
		jQuery( "#ecom_save" ).hide();
		jQuery( "#ecom_roundrobin_option" ).hide();
	}

	if( module_own == 'Contacts' )
	{
		jQuery( ".module_owner" ).html( "Assign Contacts to owner" );
		jQuery("#hide_convert").addClass("disabledbutton");
	}

	if( module_own == 'Leads' )
	{
		jQuery( ".module_owner" ).html( "Assign Leads to owner &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" );
	}


	var wpuser_sync_module = jQuery( "#choose_module" ).val();
	if( wpuser_sync_module == "Leads" )
	{
		jQuery("#Lead_owner").show();
		jQuery("#Contact_owner").hide();
	}
	else
	{
		jQuery("#Lead_owner").hide();
		jQuery("#Contact_owner").show();
	}

jQuery("#choose_module").change(function(){
	var wpsync_module = jQuery( "#choose_module" ).val();
	if( wpsync_module == "Leads" )
	{
		jQuery("#Lead_owner").show();
		jQuery("#Contact_owner").hide();
	}
	else if( wpsync_module == "Contacts" )
	{
		jQuery("#Lead_owner").hide();
		jQuery("#Contact_owner").show();
	}


	});

jQuery( "#usersync_assignedto_leads" ).change( function() {
	var assignedto_leads = jQuery(this).val();
	var wp_active_crm = jQuery("#activated_crm").val();
	jQuery.ajax({
		type : 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			'action' : 'wp_usersync_assignedto',
			'module' : 'leads',
			'active_crm' : wp_active_crm,
			'assigned_to': assignedto_leads,
			'securekey'  : leads_builder_ajax_object.nonce,
		},

		success:function(data)
		{
			swal('Success!', 'Assign to Owner Saved Successfully', 'success')
		},
		error:function(errorThrown)
		{

		}
	});

});	


jQuery( "#copy_to_clipboard" ).click( function(){
	var copy_text = jQuery("#copy_smack_host_access_key").val();
	copyToClipboard(copy_text);
});

function copyToClipboard(text) {
	window.prompt("Copy the URL and Paste in Salesforce CallBack URL", text);
	}
});

function upgrade_alert(){
	swal('Upgrade To PRO!', '', 'warning');
}

function save_campaign(){
	var camp_name = jQuery("#campaign_name").val();
	var utm_source = jQuery("#utm_source").val(); 
	var camp_medium = jQuery("#camp_medium").val();
	var utm_name = jQuery("#utm_name").val();
	var plug_url = jQuery("#plug_url").val();
	if( camp_name == '' || utm_source == '' | camp_medium == '' | utm_name == '')
	{	
		swal("Warning!", "Please fill all fields", "warning");
		return false;
	}
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'save_campaign_details',
			camp_name : camp_name,
			utm_source : utm_source,
			camp_medium : camp_medium,
			utm_name : utm_name,
			securekey : leads_builder_ajax_object.nonce,
		} ,

		success: function(data){
			window.location= "admin.php?page=lb-mailsourcing";
		},
		error: function(errorThrown){

		}

	});	
}

function finish_campaign() {
	alert("Thankyou");
}

//save apikey
function save_mc_apikey(){
	var mc_apikey = jQuery("#MC_apikey").val();
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'save_apikey',
			mc_apikey : mc_apikey,
			securekey : leads_builder_ajax_object.nonce,
		} ,

		success: function(data){
			if( data == 'null' || data == '')
			{
				swal("Warning!", "Please check your API key", "warning");
				return false;
			}
			var data = JSON.parse(data);
			var option;
			var camp_list = jQuery.map(data, function(val,el) { 
				option += '<option value="'+ el + '">' + val + '</option>';

				});

			jQuery("#camp_list").show();
			jQuery("#campaign_list").append(option);
			jQuery('#close_map_modal').click();
			jQuery("#MC_apikey").html("");
			},
		error: function(errorThrown){
				
			}

	});	
}


//Third party title
function save_thirdparty_title(thirdparty_title_key , thirdparty_title_val)
{
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'save_thirdparty_form_title',
			tp_title_key : thirdparty_title_key,
			tp_title_val : thirdparty_title_val,
			securekey  : leads_builder_ajax_object.nonce,
		} ,

		success: function(data){
		},
		error: function(errorThrown){

		}

	});
}

//Show mapped configuration
function show_map_config(map_module , map_form_title , form_id , mapped_tp_plugin , tp_roundrobin)
{

	jQuery( "#clear_contents" ).hide();
	jQuery("#mapping-modalbox").show();
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'send_mapped_config',
			form_id : form_id,
			form_title: map_form_title,	
			third_plugin : mapped_tp_plugin,
			third_module : map_module,
			third_roundrobin : tp_roundrobin,
			securekey  : leads_builder_ajax_object.nonce,
		} ,
		success: function(data){
			jQuery( "#show_form_list" ).empty();
			var data_array = JSON.parse( data );
			jQuery( "#mapping_options" ).html(data_array.map_options);
			jQuery( "#CRM_field_mapping" ).html( data_array.fields_html );
			jQuery(".selectpicker").selectpicker('refresh'); 
			jQuery(".bootstrap-select").click(function () { 
					jQuery(this).toggleClass("open"); 
				});
			jQuery(".bs-searchbox").css("width","89%","height","40px");
		},
		error: function(errorThrown){
				
		}

	});
}

function delete_map_config(third_plugin , tp_form_id)
{
	document.getElementById("smack_delete_modal").style.display = "block";
	var getDel = document.getElementById("modalDeleteId");
	getDel.setAttribute("data-third_plugin", third_plugin);
	getDel.setAttribute("data-tp_form_id", tp_form_id);
}

function deleteNow(){
	document.getElementById("smack_delete_modal").style.display = "none";

	var delObj = document.getElementById("modalDeleteId");
	var third_plugin = delObj.getAttribute("data-third_plugin");
	var tp_form_id = delObj.getAttribute("data-tp_form_id");
	document.getElementById("loading-image").style.display = "block";
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'delete_mapped_config',
			form_id : tp_form_id,
			third_plugin : third_plugin,
			securekey  : leads_builder_ajax_object.nonce,
		} ,
		success: function(data){
			document.getElementById("loading-image").style.display = "none";
			swal( 'Success!' , 'Shortcode Deleted Successfully!' , 'success' );
			window.location.reload();
		},
		error: function(errorThrown){
		}
	});
}

function create_form(Action, Module, shortcode, plugin)
{
	if( Action == "Deleteshortcode" ) {
		document.getElementById("loading-image").style.display = "block";
		jQuery.ajax({
			type: 'POST',
			url : leads_builder_ajax_object.url,
			data : {
				action : 'createnew_form',
				Action : Action,
				Module : Module,
				shortcode : shortcode,
				plugin : plugin,
				securekey : leads_builder_ajax_object.nonce,
			} ,
			success: function(data){
				var data_array = JSON.parse( data );
				document.getElementById('loading-image').style.display = "none";
				var shortcode = data_array.shortcode;
				var module = data_array.module;
				var crmtype = data_array.crmtype;
				var onAction = data_array.onAction;
				swal( 'Success!' , 'Shortcode Deleted Successfully!' , 'success' );
				window.location = "admin.php?page=lb-crmforms";
			},
			error: function(errorThrown){
					
			}
		});
	}
	else{
		document.getElementById("loading-image").style.display = "block";
		jQuery.ajax({
			type: 'POST',
			url : leads_builder_ajax_object.url,
			data : {
				action : 'createnew_form',
				Action : Action,
				Module : Module,
				shortcode : shortcode,
				plugin : plugin,
				securekey : leads_builder_ajax_object.nonce,
			} ,
			success: function(data){
				var data_array = JSON.parse( data );
				document.getElementById('loading-image').style.display = "none";
				var shortcode = data_array.shortcode;
				var module = data_array.module;
				var crmtype = data_array.crmtype;
				var onAction = data_array.onAction;
				if(Action == 'Editshortcode') {
					window.location = "admin.php?page=lb-create-leadform&__module=ManageShortcodes&__action=ManageFields&onAction="+onAction+"&crmtype="+crmtype+"&module="+module+"&EditShortcode="+shortcode+"";
				} else {
					window.location = "admin.php?page=lb-crmforms";
				}
			},
			error: function(errorThrown){
					
				}
		});
	}
}

function remove_map_contents()
{
	jQuery( "#CRM_field_mapping" ).html("");
	jQuery( "#mapping_options" ).html("");
	jQuery( "#CRM_field_mapping" ).css({'color':'','font-size':'','margin-left':''});
	jQuery( "#map_thirdparty_module" ).val( "none" );
	jQuery( "#map_thirdparty_form" ).val("none");
	jQuery( "#thirdparty_form_title" ).val( "--None--");
	jQuery( "#display_form_lists" ).html(""); location.reload();
}

//MAPPING CRM FIELDS

function get_mapping_configuration(thirdparty_form)
{
	var thirdparty_module = jQuery( "#map_thirdparty_module" ).val();
	if( thirdparty_module == "none" )
	{	
		alert("kindly choose module to map" );
		return false;
	}
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'send_mapping_configuration',
			thirdparty_module : thirdparty_module,
			thirdparty_plugin : thirdparty_form,
			securekey : leads_builder_ajax_object.nonce,
		} ,
		success: function(data){
			jQuery( "#display_form_lists" ).html( data);
			jQuery(".selectpicker").selectpicker('refresh');
			jQuery(".bootstrap-select").click(function () {
					jQuery(this).toggleClass("open");
				});
			jQuery(".bs-searchbox").css("width","89%","height","40px");
		},
		error: function(errorThrown){

		}

	});
}

function get_thirdparty_title( form_title , thirdparty_plugin , module )
{
	//Clear css
	jQuery( "#CRM_field_mapping" ).css({'color':'','font-size':'','margin-left':''});
	//END

	var thirdparty_title = form_title;
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'get_thirdparty_fields',
			form_title : thirdparty_title,
			third_plugin : thirdparty_plugin,
			third_module : module,
			securekey : leads_builder_ajax_object.nonce,
		} ,
		success: function(data){
			var data_array = JSON.parse( data );	
			jQuery( "#mapping_options" ).html(data_array.map_options);
			jQuery( "#CRM_field_mapping" ).html( data_array.fields_html );
			jQuery('.selectpicker').selectpicker('refresh');
			jQuery(".bootstrap-select").click(function () { jQuery(this).toggleClass("open"); });
			jQuery(".bs-searchbox").css("width","89%","height","40px");
		},
		error: function(errorThrown){

		}

	});
}


//MAPPING ECOM fields with CRM fields

function change_ecom_configuration(id)
{
	document.getElementById('loading-image').style.display = "block";
	var ecom_module = jQuery( "#ecom_module" ).val();
	var active_crm = jQuery( "#ecom_active_crm" ).val();
	jQuery( "#choose_owner" ).show();
	jQuery( "#hide_convert" ).show();
	jQuery( "#ecom_roundrobin_option" ).show();

	if( ecom_module == 'Contacts' )
	{
		jQuery( ".module_owner" ).html( "Assign Contacts to Owner" );
	}
	else if( ecom_module == 'Leads' )
	{
		jQuery( ".module_owner" ).html( "Assign Leads to Owner &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" );
	}
	//NEW

	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'change_ecom_module_config',
			ecom_module : ecom_module,
			ecom_active_crm : active_crm,
			securekey   : leads_builder_ajax_object.nonce,
		} ,
		success: function(data){
			jQuery( "#load_ecom_fields" ).html("");
			jQuery("#load_ecom_fields").html( data );
			var ecom_owner = jQuery( "#ecom_wc_owner" ).val();
			jQuery( "#ecom_mapping_assignedto" ).val( ecom_owner );

			var ecom_module_now = jQuery( "#ecom_module" ).val();
			if( ecom_module_now == 'Not Enabled' )
			{
				jQuery( "#choose_owner" ).hide();
				jQuery( "#hide_convert" ).hide();
				jQuery( "#load_ecom_fields" ).hide();
				jQuery( "#ecom_save" ).hide();
				jQuery( "#ecom_roundrobin_option" ).hide();
			}

			if( ecom_module_now == 'Contacts')
			{
				jQuery( "#choose_owner" ).show();
				jQuery( "#hide_convert" ).show();
				jQuery("#hide_convert").addClass("disabledbutton");
				jQuery( "#load_ecom_fields" ).show();
				jQuery( "#ecom_save" ).show();
				jQuery( "#ecom_roundrobin_option" ).show();
			}
			else if( ecom_module_now == 'Leads' )
			{
				jQuery( "#choose_owner" ).show();
				jQuery( "#hide_convert" ).show();
				jQuery("#hide_convert").removeClass("disabledbutton");
				jQuery( "#load_ecom_fields" ).show();
				jQuery( "#ecom_save" ).show();
				jQuery( "#ecom_roundrobin_option" ).show();
			}
			jQuery(".selectpicker").selectpicker('refresh'); 
			jQuery(".bootstrap-select").click(function () { 
				jQuery(this).toggleClass("open"); 
				});
			jQuery(".bs-searchbox").css("width","89%","height","40px");
			document.getElementById('loading-image').style.display = "none";
		},
		error: function(errorThrown){
			}

		});
}

function map_ecom_crm_fields( )
{
	var ecom_module = jQuery( "#ecom_module" ).val();
	var ecom_assignedto = jQuery( "#ecom_mapping_assignedto" ).val();
	var ecom_count = jQuery( "#ecom_total_field_count" ).val();
	var ecom_mandatory = jQuery( "#ecom_crm_mandatory_fields").val();
	var count = jQuery( "#ecom_total_field_count" ).val();
	var ecom_crm_man_fields = JSON.parse( ecom_mandatory );
	var flag , error = 0 , errormessage = "";
	var Repeat_fields = false , mapping_fields = [] , save_repeated_fields = [];

	for( var i=0 ; i < ecom_crm_man_fields.length ; i++ )
	{
		flag = false;
		for( j = 1 ; j < count ; j++ )
		{
			var check_man_field = "#ecom_crm_fields_" +j;
			selected_val = jQuery( check_man_field ).val();
			if( selected_val == ecom_crm_man_fields[i] )
			{
				flag = true;
			}
		}
		if( flag == false )
		{
			errormessage += ecom_crm_man_fields[i] + " is a mandatory field. It must be mapped\n";
			error++;
		}
	}


	for( var i=1; i<count ; i++ )
	{
		var crm_field_name = "#ecom_crm_fields_" +i;
		selected_val = jQuery( crm_field_name ).val();
		if( mapping_fields.indexOf( selected_val ) != -1 && selected_val != "" && selected_val != "--None--" )
		{
			Repeat_fields = true;
			//	save_repeated_fields[i] = selected_val; 
		}
		mapping_fields[ i ] = selected_val;

	}

	if( error > 0 )
	{

		swal("Warning!",  errormessage, "warning");
		return false;
	}


	if( Repeat_fields == true )
	{

		swal("Warning!", "Mapped Fields should not be repeated", "warning");
		return false;
	}
	swal('Success!', 'Mapped Successfully', 'success');
	var config_data = JSON.parse( "" || "{}");
	var items = jQuery("form :input").map(function(index, elm) {
			return {name: elm.name, type:elm.type, value: jQuery(elm).val()};
			});

	jQuery.each(items, function(i, d){
			if(d.value != '' && d.value != null)
			config_data[d.name] = d.value;
			});

	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data: {
			'action'  : 'map_ecom_fields',
			'postdata': config_data,
			'ecom_module': ecom_module,
			'ecom_assigned_to':ecom_assignedto,
			'securekey'     : leads_builder_ajax_object.nonce,
		},
		success:function(data){

		},
		error: function(errorThrown){

		}
	});

}

//Map Existing Forms
function map_thirdparty_crm_fields()
{
	var count = jQuery( "#total_field_count" ).val();
	var tp_module= jQuery( "#module" ).val();
	var tp_title = jQuery( "#form_name" ).val();
	var tp_crm = jQuery( "#active_crm" ).val();
	var tp_plugin = jQuery( "#thirdparty_plugin" ).val();
	var tp_duplicate = jQuery("#duplicate_handling").val();

	var tp_assignedto = jQuery("#mapping_assignedto").val();
	var assignedto_name = jQuery("#mapping_assignedto option:selected").text();
	var crm_mandatory_fields = jQuery( "#crm_mandatory_fields" ).val();
	var crm_man_fields = JSON.parse( crm_mandatory_fields );

	var crm_mandatory_fields_hidden = jQuery( "#crm_mandatory_fields_hidden" ).val();
	var crm_man_fields_hidden = JSON.parse( crm_mandatory_fields_hidden );

	var flag , error = 0 , errormessage = "";
	var Repeat_fields = false , mapping_fields = [] , save_repeated_fields = [];

	for( var i=0 ; i < crm_man_fields.length ; i++ )
	{
		flag = false;
		for( j = 1 ; j < count ; j++ )
		{
			var check_man_field = "#crm_fields_" +j;
			selected_val = jQuery( check_man_field ).val();
			// if( selected_val == crm_man_fields[i] )
			if( selected_val == crm_man_fields_hidden[i] )
			{
				flag = true;
			}
		}
		if( flag == false )
		{
			errormessage += crm_man_fields[i] + " is a mandatory field. It must be mapped\n";
			error++;
		}
	}


	for( var i=1; i<count ; i++ )
	{
		var crm_field_name = "#crm_fields_" +i;
		selected_val = jQuery( crm_field_name ).val();
		if( mapping_fields.indexOf( selected_val ) != -1 && selected_val != "" && selected_val != "--None--" )
		{
			Repeat_fields = true;
			save_repeated_fields[i] = selected_val; 
		}
		mapping_fields[ i ] = selected_val;

	}

	if( error > 0 )
	{
		swal("Warning!", errormessage, "warning");
		return false;
	}


	if( Repeat_fields == true )
	{
		swal("Warning!", "Mapped Fields should not be repeated", "warning");
		return false;
	}

	var config_data = JSON.parse( "" || "{}");
	var items = jQuery("form :input").map(function(index, elm) {
			return {name: elm.name, type:elm.type, value: jQuery(elm).val()};
			});

	jQuery.each(items, function(i, d){
			if(d.value != '' && d.value != null)
			config_data[d.name] = d.value;
			});

	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'map_thirdparty_fields',
			post_data: config_data,
			form_title : tp_title,
			third_plugin : tp_plugin,
			third_module : tp_module,
			third_crm : tp_crm,
			third_duplicate : tp_duplicate,
			third_assigedto : tp_assignedto,
			assignedto_name : assignedto_name,
			securekey     : leads_builder_ajax_object.nonce,
		} ,
		success: function(data){
			jQuery( "#show_form_list" ).html("");
			jQuery( "#mapping_options" ).html("");
			swal('Success!', 'Mapped Successfully', 'success');
		},
		error: function(errorThrown){

			}

		});
}

//RR User sync
function get_roundrobin_option()
{
	var tp_roundrobin = jQuery("input[type=checkbox][name=enable_roundrobin_usersync]").is(':checked'); 
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'save_usersync_RR_option',
			user_rr_val : tp_roundrobin,
			securekey : leads_builder_ajax_object.nonce,
		} ,
		success: function(data){
			swal('Success!', 'Settings Saved Successfully', 'success')
		},
		error: function(errorThrown){

		}
	});
}

//END MAPPING CRM FIELDS
function TFA_Authkey_Save( auth_val)
{
	var TFA_authtoken = auth_val;	
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'TFA_auth_save',
			authtoken : TFA_authtoken,
			securekey : leads_builder_ajax_object.nonce,
		} ,
		success: function(data){
		},
		error: function(errorThrown){

		}

	});		
}

function validateCrmStuffFetched()
{
	document.getElementById('loading-image').style.display = "block";

	var  data_array = {
		'action'        : 'adminAllActionsPRO',
		'operation'     : 'NoFieldOperation',
		'doaction'      : 'CheckFetchedDetails',
		'securekey'     : leads_builder_ajax_object.nonce,
	};

	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: data_array,
		
		success:function(data) {
			jQuery("#settingsavedmessage").css('display' , 'block');
			jQuery("#settingsavedmessage").html('Saved');
			jQuery("#settingsavedmessage").css('display','inline').fadeOut(3000);
			document.getElementById('loading-image').style.display = "none";
		},
		error: function(errorThrown){

		}
	});
}

function validateMapFields( siteurl , mapvariable , userfield , mandatory_array )
{
	var totaluserfields = jQuery("#totaluserfields").val();
	var flag , error = 0 , errormessage = "";
	var Repeating_field = false , mappedfields = [],save_repeated_fields = [];
	for( var i=0 ; i < mandatory_array.length ; i++ )
	{
		flag = false;
		for( j = 0 ; j < totaluserfields ; j++ )
		{
			if( document.getElementsByName(mapvariable+"[]")[j].value == mandatory_array[i] )
			{
				flag = true;
			}
		}
		if( flag == false )
		{
			errormessage += mandatory_array[i] + " is a mandatory field. It must be mapped\n";
			error++;
		}
	}

	for( var i = 0 ; i < totaluserfields ; i++ )
	{
		selected_value = document.getElementsByName(mapvariable+"[]")[i].value
			if( mappedfields.indexOf( selected_value ) != -1 && selected_value != "" )
			{
				Repeating_field = true;
				save_repeated_fields[i] = selected_value;
			}
		mappedfields[ i ] = selected_value;
	}

	document.getElementById('loading-image').style.display = "none";
	if( error > 0 )
	{

		swal("Warning!", errormessage, "warning");
		return false;
	}
	if( Repeating_field == true )
	{
		swal("Warning!", "Mapped Fields should not be repeated", "warning");
		return false;
	}

	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data: {
			'action'  : 'map_sync_user_fields',
			'mappedfields': mappedfields,
			'totaluserfields' : totaluserfields,
			'mapvariable' : mapvariable,
			'securekey'   : leads_builder_ajax_object.nonce,	    
		},
		success:function(data){
			swal('Success!', 'Mapped Successfully', 'success')
		},
		error: function(errorThrown){
			window.location.reload();
		}
	});
}

function saveFormSettings( shortcodename )
{
	var formtype = jQuery("select[name=formtype]").val();
	var duplicate_handling = jQuery("input[type=radio][name=check_duplicate]:checked").val();
	var assignedto = jQuery("select[name=assignedto]").val();
	var assignemail = jQuery("select[name=assignedto] option:selected").text();
	var errormessage = jQuery("input[name=errormessage]").val();
	var successmessage = jQuery("input[name=successmessage]").val();
	var enableurlredirection = jQuery("input[type=checkbox][name=enableurlredirection]").is(':checked');
	var redirecturl = jQuery("input[name=redirecturl]").val();
	var enablecaptcha = jQuery("input[type=checkbox][name=enablecaptcha]").is(':checked');
	//var savedetails = '<br>FormType &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' + formtype + '<br>' + 'Shortcode &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:  ' + shortcodename + '<br>' + 'Assignee&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' + assignemail   ;

	jQuery("select[name=assignedto]").change(function(){
			var option_selected = jQuery(this).find("option:selected").text();
			});

	var redirect = jQuery("#redirecturl").val();
	var thirdparty_form_type = jQuery("#thirdparty_form_type").val();
	var thirdparty_title = jQuery("#thirdparty_form_title").val();
	var thirdparty_option_available = jQuery("#thirdparty_option_available").val();
	if( redirect.length > 0 )
	{
		var redir_postid = '<br>Redir Post-id &nbsp;:' + redirect;
	}

	document.getElementById('loading-image').style.display = "block";

	var  data_array = {
		'action'        : 'adminAllActionsPRO',
		'operation'	    : 'NoFieldOperation',
		'doaction'      : 'SaveFormSettings',
		'shortcode'     : shortcodename,
		'formtype'	    : formtype,
		'duplicate_handling' : duplicate_handling,
		'assignedto'    : assignedto,
		'errormessage'  : errormessage,
		'successmessage': successmessage,
		'enableurlredirection' : enableurlredirection,
		'redirecturl'   : redirecturl,
		'enablecaptcha' : enablecaptcha,
		'thirdparty_title' : thirdparty_title,
		'thirdparty_form_type': thirdparty_form_type,
		'securekey'     : leads_builder_ajax_object.nonce,
	};

	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: data_array,
		
		success:function(data) {
			jQuery("#url_post_id").css('display' , 'block');
			jQuery("#url_post_id").html(redir_postid);
			jQuery("#url_post_id").css('display','inline').fadeOut(3800);	
			swal('Success!' , 'Settings Saved Successfully!' , 'success' );
			document.getElementById('loading-image').style.display = "none";
			if(thirdparty_option_available == 'no' && thirdparty_form_type != 'none')
			{
				jQuery('#generate_forms').click();
			}
		},
		error: function(errorThrown){

		}
	});
}

function enableCaptchaPRO(siteurl,module, option, onAction)
{
	var selected = true;
	document.getElementById('loading-image').style.display = "block";

	if(jQuery('#isWidget').prop('checked'))
	{
		var checked = true;
		selected = false;
	}
	else
	{
		var checked = false;
	}
	var shortcode = '';
	if(onAction == 'onEditShortCode')
	{
		shortcode = jQuery('#shortcode').val();
	}
	var  data_array = {
		'action'	    : 'adminAllActionsPRO',
		'doaction'	    : 'SwitchWidget',
		'adminaction'   : 'isWidget',
		'module'	    : module,
		'option'	    : option,
		'onAction'	    : onAction,
		'shortcode'	    : shortcode,
		'checked'	    : checked, 
		'selected'	    : selected,
		'securekey'     : leads_builder_ajax_object.nonce,
	};

	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: data_array,
		
		success:function(data) {
			if(data.indexOf("true") != -1)
			{
				jQuery("#isWidget_status").html('Saved');
				jQuery("#isWidget_status").css('display','inline').fadeOut(3000);
			}
			else
			{
				jQuery("#isWidget_status").html('Not Saved');
				jQuery('#isWidget').attr("checked", selected);
				jQuery("#isWidget_status").css('display','inline').fadeOut(3000);
			}
			document.getElementById('loading-image').style.display = "none";
		},
		error: function(errorThrown){

		}
	});
}

function selectedPlugPRO( thiselement )
{
	var x = document.getElementById("pluginselect").selectedIndex;
	var select = document.getElementsByTagName("option")[x].value;
	var old_crm_pro = jQuery( "#revert_old_crm_pro" ).val();
	var get_config = jQuery( "#get_config" ).val();
	if( get_config == 'no' )
	{
		var pluginselect_value;

		for(var i = 0; i < pluginselect.length; i++){
			if(pluginselect[i].selected == true){
				pluginselect_value = pluginselect[i].value;
			}
		}

		var redirectURL=document.getElementById('plug_URL').value;
		var postdata = pluginselect_value;

		jQuery.ajax({
			type: 'POST',
			url: leads_builder_ajax_object.url,
			data: {
				'action'   : 'selectplugpro',
				'postdata' : postdata,
				'select'   : select,
				'securekey'     : leads_builder_ajax_object.nonce,
				},
			success:function(data){
				var data = data.replace(/\s/g, '') ;

				if( data == "yes" )
				{
					location.reload(true);
				}
				else
				{
					swal("Warning!", "Plugin inactive. You should install the addon from the above mentioned addon links ", "warning");
					jQuery("#pluginselect").selectpicker('val', old_crm_pro);
				}
			},
			error: function(errorThrown){

			}
		});
	}
	else
	{
		document.getElementById("smack_confirm_modal").style.display = "block";
	}
}

function changecrm(){
	document.getElementById("smack_confirm_modal").style.display = "none";
	var pluginselect_value;
	for(var i = 0; i < pluginselect.length; i++){
		if(pluginselect[i].selected == true){
			pluginselect_value = pluginselect[i].value;
		}
	}
	var redirectURL=document.getElementById('plug_URL').value;
	var postdata = pluginselect_value;
	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: {
		'action'   : 'selectplugpro',
		'postdata' : postdata,
		'securekey'     : leads_builder_ajax_object.nonce,
		},
		success:function(data){
			var data = data.replace(/\s/g, '') ;

			if( data == "yes" )
			{
				location.reload(true);
			}
			else
			{
				swal("Warning!", "Plugin inactive. You should install the addon from the above mentioned addon links ", "warning");
				jQuery("#pluginselect").selectpicker('val', old_crm_pro);
			}
		},
		error: function(errorThrown){
		
		}
	});
}


function wpSyncSettingsPRO( thiselement ) {

	var active_crm = jQuery( "#activated_crm" ).val();
	var selected_module = jQuery( "#choose_module option:selected" ).val();
	if( active_crm == 'freshsales' && selected_module == 'Contacts') {
		$("select option[value*='create']").prop('disabled',true);
	} else if (active_crm == 'freshsales' && selected_module != 'Contacts') {
		$("select option[value*='create']").prop('disabled',false);
	}
	jQuery.ajax({
		type : 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			'action' : 'Sync_settings_PRO',
			'module' : selected_module,
			'active_crm' : active_crm,
			'securekey'  : leads_builder_ajax_object.nonce,
		},

		success:function(data)
		{
			swal('Success!', 'User Sync Settings Saved Successfully', 'success')
			location.reload();
		},
		error:function(errorThrown)
		{

		}
		});
}

function wpSyncDuplicateSettingsPRO( thiselement ) {

	var duplicate_option = jQuery( "#duplicate_handling option:selected" ).val();
	var active_crm = jQuery( "#activated_crm" ).val();
	jQuery.ajax({
		type : 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			'action' : 'Sync_settings_PRO',
			'duplicate_handling' : duplicate_option,
			'active_crm' : active_crm,
			'securekey'  : leads_builder_ajax_object.nonce,
		},

		success:function(data)
		{
			swal('Success!', 'Duplicate Settings Saved Successfully', 'success')
		},
		error:function(errorThrown)
		{

		}
	});
}

function enableWPUserAutoSync(id) {
	if(document.getElementById("enableAutoSync").checked == true) {
		var autosync_val = "On";
	} else {
		var autosync_val = "Off";
	}

	jQuery.ajax({
		type : 'POST',
		url : leads_builder_ajax_object.url,
		data:  {
			'action' : 'saveSyncValue' ,
			'syncedvalue' : autosync_val,	
			'securekey'   : leads_builder_ajax_object.nonce,
		}, 
		success:function(data)
		{
		},
		error:function(errorThrown)
		{

		}
	});
}

function mappingModulePRO( id )
{
	var mapping_module = jQuery("#mappingmodule").val();
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data: {
			'action'  : 'mappingmodulepro',
			'postdata': mapping_module,
			'securekey' : leads_builder_ajax_object.nonce,
		},
		success:function(data){

		},
		error: function(errorThrown){

		}
	});

}

function selectedcustomPRO( plugname )
{
	var custom_plugin = jQuery("#custom_fields option:selected").val();
	jQuery("#custom_fields").val(custom_plugin);

	var old_custom_plugin = jQuery( "#custom_plugin_value" ).val();
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data: {
			'action'  : 'customfieldpro',
			'postdata': custom_plugin,	
			'securekey' : leads_builder_ajax_object.nonce,
		},
		success:function(data){
			if( data == "yes" )
			{
				jQuery( "#custom_plugin_value" ).val(custom_plugin);	
				swal('Success!', 'Custom Field Settings Saved Successfully', 'success')
			}
			else
			{
				swal("Warning!", "Plugin inactive. You should activate the plugin first", "warning");
				jQuery("#custom_fields").selectpicker('val',old_custom_plugin);
			}
		},
		error: function(errorThrown){
		},
	})
}


function goToTopPRO()
{
	jQuery(window).scrollTop(0);
}

function drop_table_key(){
	if(document.getElementById("droptable").checked == true) {
		var droptable = "on";
	} else {
		droptable = "off";
	}
	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: {
			'action'   : 'droptable_info',
			'droptable' :droptable,
			'securekey' : leads_builder_ajax_object.nonce,
		},
		success : function(data) {
			swal('Success!', 'Settings Saved Successfully', 'success')		
		},		
		error : function(errorThrown) {		
		}		
	});
		
}
function upload_function(){
	var value = jQuery( "#actual-btn" ).val();
	jQuery.ajax({
		type:'POST',
		url:leads_builder_ajax_object.url,
		data:{
			action: 'file_import',
			'value':value,
			'securekey' : leads_builder_ajax_object.nonce,
		},
		success :function(data){
			swal('Success!','Import Successfully','success')
		},
		error: function(errorThrown){

		 }
	});
}
function DownloadJSON(value) {
	var data_array = "";
	jQuery.ajax({
		type: 'POST',
		url : leads_builder_ajax_object.url,
		data : {
			action : 'download_json',
			'value':value,
			'securekey' : leads_builder_ajax_object.nonce,
		} ,
		success: function(data){
			data_array = JSON.parse( data );
			var name=data_array['CRM_FORMS']['third_plugin'];
			var third_plugin_name=name+".json";
			var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(data_array));
			var atag = document.createElement("a");
			atag.setAttribute("href", dataStr);
			atag.setAttribute("download", third_plugin_name);
			atag.click();
		
		}
	});
	

}
function save_captcha_key()
{
	var emailcondition = jQuery( "#emailcondition" ).val();
	var email = jQuery( "#email" ).val();
	if(document.getElementById("debugmode").checked == true) {
		var debugmode = "on";
	} else {
		debugmode = "off";
	}
	if (jQuery('#smack_recaptcha_no').is(":checked")) {
		var smack_recaptcha = "no";
	} else {
		smack_recaptcha = "yes";
	}
	var recaptcha_public_key = jQuery("#smack_public_key").val();
	var recaptcha_private_key = jQuery("#smack_private_key").val();
	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: {
			'action'   : 'captcha_info',
			'emailcondition' : emailcondition,
			'email': email,
			'debugmode' : debugmode,
			'smack_recaptcha'  : smack_recaptcha,
			'recaptcha_public_key' : recaptcha_public_key,
			'recaptcha_private_key' : recaptcha_private_key,
			'securekey'     : leads_builder_ajax_object.nonce,
		},
		success : function(data) {
			swal('Success!', 'Settings Saved Successfully', 'success')
			location.reload(true);

		},

		error : function(errorThrown) {

		}

	});

}

function showOrHideRecaptchaPRO(option)
{
	if(option == "no")
	{
		jQuery("#recaptcha_public_key").css("display",'none');
		jQuery("#recaptcha_private_key").css("display",'none');
	}
	else
	{
		jQuery("#recaptcha_public_key").css("display",'block');
		jQuery("#recaptcha_private_key").css("display",'block');
	}
}

function captureAlreadyRegisteredUsersPRO(siteurl)
{
	jQuery( ".display_success" ).hide();
	var get_mapping_value = jQuery( "#check_mapping_value" ).val();
	if( get_mapping_value == 'no' )
	{
		swal("Warning!", "Mapping Fileds Not Yet Configured . Click Configure Mapping", "warning");
		return false;
	}
	else
	{
		var start = jQuery( "#wp_start" ).val();
		var offset = jQuery( "#wp_offset" ).val();
		var siteurl = jQuery( "#site_url" ).val();
		var synced_count = jQuery( "#wp_synced_count" ).val();
		document.getElementById('loading-image').style.display = "block";
		jQuery.ajax({
			type: 'POST',
			url: leads_builder_ajax_object.url,
			data: {
				'action'   : 'adminAllActionsPRO',
				'operation': 'NoFieldOperation',
				'doaction' : 'CaptureAllWpUsers',
				'wp_start' : start,
				'wp_offset': offset,
				'synced_count' : synced_count,
				'siteurl'  : siteurl,
				'securekey' : leads_builder_ajax_object.nonce,
			},
			success:function(data) {
				var result = JSON.parse( data );
				start = parseInt( result.start );
				offset = parseInt( result.offset );
				var duplicate_option = result.duplicate_option;
				var need_to_sync = result.total_count - ( result.synced_count );
				var last_id = parseInt( result.last_user_id );
				var users_within_limit = result.users_within_limit;
				var synced_count = result.synced_count;	
				if( start <= result.total_count )
				{
					Notify("Total users : " + result.total_count  + "<br>Users synced : " +  (result.synced_count ) + "<br>" +  "Need to be synced : " + need_to_sync,null,null,'success');
					document.getElementById('loading-image').style.display = "none";
					jQuery( "#wp_start" ).val(start);
					jQuery( "#wp_offset" ).val(offset);
					jQuery( "#wp_synced_count" ).val(synced_count);
					captureAlreadyRegisteredUsersPRO( );
				}
				if( start > result.total_count ) 
				{
					Notify("Total Users synced : " + result.total_count,null,null,'success');
					document.getElementById('loading-image').style.display = "none";
					jQuery( "#wp_start" ).val("0");
					jQuery( "#wp_offset" ).val("10");
					jQuery( "#wp_synced_count" ).val("0");
				}  
			},
			error: function(errorThrown){
					
			}
		});
	} // check mapping
}

function createNewShortcodePRO(siteurl,formid, module, option, onAction, slug)
{

	var shortcode = '';
	var moduleaction;
	if(module == "Contacts")
	{
		moduleaction = "Contact";
	}
	else
	{
		moduleaction = "Lead";
	}

	if(onAction == 'onEditShortCode')
	{
		shortcode = jQuery('#shortcode').val();
	}

	var  data_array = {
		'action'        : 'adminAllActionsPRO',
		'doaction'      : 'CreateNewFieldShortcode',
		'adminaction'   : 'createNewShortcode',
		'module'        : module,
		'option'        : option,
		'onAction'      : onAction,
		'shortcode'     : shortcode,
		'securekey'     : leads_builder_ajax_object.nonce,
	};
	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: data_array,
		
		success:function(data) {
			window.location.href= siteurl+'/wp-admin/admin.php?page='+slug+'/index.php&action=ManageFields&module='+module+'&EditShortCode='+data;
		},
		error: function(errorThrown){

		}
	});
}


function salesFetch( )
{
	syncCrmFieldsPRO();
}

function saveCRMConfiguration( id ) {
	document.getElementById('loading-image').style.display = "block";
	var siteurl = jQuery( "#site_url" ).val();
	var active_plugin = jQuery( "#active_plugin" ).val();

	if( active_plugin == "wptigerpro" )
	{
		var vtiger_host_url = jQuery("#smack_tiger_host_address" ).val();
		if( vtiger_host_url.match(/index.php/))
		{
			var tiger_replaced_host_url = vtiger_host_url.replace( /index.php/ , "" );
			if( tiger_replaced_host_url.slice(-1) == "/" )
			{
				tiger_replaced_host_url = tiger_replaced_host_url.substr( 0, tiger_replaced_host_url.length - 1 );
			}
			jQuery("#smack_tiger_host_address").val(tiger_replaced_host_url);
		}
	}

	if( active_plugin == "wpsugarpro" || active_plugin == "wpsuitepro" )
	{
		var sugar_host_url = jQuery("#smack_sugar_host_address" ).val();
		if( sugar_host_url.match(/index.php/))
		{
			var sugar_replaced_host_url = sugar_host_url.replace( /index.php/ , "" );
			if( sugar_replaced_host_url.slice(-1) == "/" )
			{
				sugar_replaced_host_url = sugar_replaced_host_url.substr( 0, sugar_replaced_host_url.length - 1 );
			}
			jQuery("#smack_sugar_host_address").val(sugar_replaced_host_url);
		}
	}

	if(active_plugin == 'wpsuitepro'){
		jQuery.ajax({
			type : 'POST',
			url  : leads_builder_ajax_object.url,
			data :{
				'action' : 'SaveSuiteconfig',
				'securekey' : leads_builder_ajax_object.nonce,
			},
			success:function( data )
			{

			},
			error:function( errorThrown )
			{

			}

		});
	}

	var leads_fields_tmp = jQuery( "#leads_fields_tmp" ).val();
	var contact_fields_tmp = jQuery( "#contact_fields_tmp" ).val();
	var leads = "Leads";
	var contact = "Contacts";
	var create = "onCreate";
	var config_data = JSON.parse( "" || "{}");
	var items = jQuery("form :input").map(function(index, elm) {
		return {name: elm.name, type:elm.type, value: jQuery(elm).val()};
	});

	jQuery.each(items, function(i, d){
		if(d.value != '' && d.value != null)
		config_data[d.name] = d.value;
	});

	jQuery.ajax({
		type : 'POST',
		url  : leads_builder_ajax_object.url,
		data :{
			'action' : 'SaveCRMconfig',
			'doaction': 'Saveandfetch',
			'posted_data' : config_data,
			'securekey'  : leads_builder_ajax_object.nonce,
		},
		success:function( data )
		{
			var data = JSON.parse( data );
			if( data.error == 0 )
			{
				document.getElementById('loading-image').style.display = "none";
				document.getElementById('loading-sync').style.display = "block";

				syncCrmFieldsPRO(siteurl , active_plugin , leads , leads_fields_tmp , create, contact, contact_fields_tmp, 'leads' );

			}
			else if(data.error == 11 )
			{
				document.getElementById( 'loading-image').style.display = "none";
				swal("Warning!", data.display, "warning")
			}
			else
			{
				document.getElementById('loading-image').style.display = "none";
				swal("Warning!", data.display, "warning")
			}
		},
		error:function( errorThrown )
		{
			
		}
	} );
}

function assignedUsers(siteurl, option, onAction, shortcode)
{

	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: {
			'action'	 : 'adminAllActionsPRO',
			'doaction' 	 : 'FetchAssignedUsers',
			'siteurl'	 : siteurl,
			'option'	 : option,
			'onAction'	 : onAction,
			'shortcode'	 : shortcode,
			'securekey'  : leads_builder_ajax_object.nonce,
		},
		success:function(data) {
		//Notify("Users Synced Successfully",null,null,'success');
			var check_shortcode_availability = jQuery("#check_shortcode_availability").val();
			var count_shortcode = jQuery("#count_shortcode").val();
			if( check_shortcode_availability == 'no' ){
				for( var i= count_shortcode;i<2;i++)
				create_form('createshortcode','Leads','post', '');
			}
			document.getElementById('loading-sync').style.display = "none";
			swal('Success!', 'Settings Saved Successfully', 'success');
			window.location= "admin.php?page=lb-crmforms";
		},
		error: function(errorThrown){
				
		}
	});
}       


function syncCrmFieldsPRO(siteurl, crmtype, module, option, onAction, contactmodule, contact_fields_tmp, call_back)
{
	//Clear CSS
	var shortcode = '';
	if(onAction == 'onEditShortCode')
	{
		shortcode = jQuery('#shortcode').val();
	}
	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: {
			'action'	 : 'adminAllActionsPRO',
			'doaction' 	 : 'FetchCrmFields',
			'siteurl'	 : siteurl,
			'module'	 : module,
			'crmtype'	 : crmtype,
			'option'	 : option,
			'onAction'	 : onAction,
			'shortcode'	 : shortcode,
			'securekey'  : leads_builder_ajax_object.nonce,
		},
		success:function(data) {
			if(module === 'Leads'){
				Notify( module + " fields Synced Successfully",null,null,'success');
			}	

			var active_plugin = jQuery( "#active_plugin" ).val();
			if(call_back == 'leads') {
				syncCrmFieldsPRO(siteurl, active_plugin, contactmodule, contact_fields_tmp, onAction, contactmodule, contact_fields_tmp, 'syncuser');
			}
			if(call_back == 'syncuser') {
				assignedUsers( siteurl , active_plugin , 'options' );
			}
		},
		error: function(errorThrown){
				
			}
	});
}

function SaveCheckPRO(siteurl, module, option, shortcode ,onAction)
{
	var crmtype = document.getElementById("lead_crmtype").value;
	document.getElementById('loading-image').style.display = "block";
	var bulkaction = jQuery("#bulk-action-selector-top option:selected").text();
	var form =document.getElementById('field-form');
	var chkall = form.elements['selectall'];
	var chkBx_count = form.elements['no_of_rows'].value;
	var chkArray = new Array;
	var labelArray = new Array;
	var orderArray = new Array;
	var a = 0;
	var i;

	if(chkall.checked == true) {
		for (i=0; i < chkBx_count; i++) {
			if(document.getElementById('select'+i).disabled === false) {
				document.getElementById('select'+i).checked = true;
				var element_id = 'select' + i;
				var element_name = jQuery('#'+element_id).attr('name');
				var get_field_id = element_name.split("select");
				chkArray.push(get_field_id[1]);
			}
		}
	} else {
		for (i=0; i < chkBx_count; i++) {
			if(document.getElementById('select'+i).disabled == true)
				document.getElementById('select'+i).checked = false;
			if (jQuery('#select'+i).is(":checked")) {
				var element_id = 'select' + i;
				var element_name = jQuery('#'+element_id).attr('name');
				var get_field_id = element_name.split("select");
				chkArray.push(get_field_id[1]);
			}
		}
	}

	for(i=0; i < chkBx_count; i++) {
		var Label=document.getElementById('field_label_display_'+i).value;
		labelArray.push(Label);
	}
	jQuery("#sort_table").find('tr').each(function (i, el) {
			if( i != 0){
			var tds = jQuery(this).find('td.tdsort');
			var idx = tds.eq(0).find('input').attr('id');
			var get_id = idx.split("select");
			var changed_pos = parseInt(get_id[1]);
			orderArray.push(changed_pos);
			}
			});
	var chkarray = JSON.stringify(chkArray);
	var labelarray = JSON.stringify(labelArray);
	var orderarray = JSON.stringify(orderArray);
	/*var shortcode = '';
	  if(onAction == 'onEdit')
	  {
	  shortcode = jQuery('#shortcode').val();
	  }*/
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
			'securekey'  : leads_builder_ajax_object.nonce,
		},
		success:function(data) {
		
			document.getElementById('loading-image').style.display = "none";
			if(data == "Not synced")
			{
				alert("Must Fetch fields before Saving Settings");
				flag = false;
				return false;
			}
			else
			{
				window.location.reload(true);
			}
		},
		error: function(errorThrown){
		}
	});
	return flag;
}

function SelectFieldsPRO(siteurl, module, option, onAction)
{
	var crmtype;
	var module;

	module = document.getElementById("module").value;
	crmtype = document.getElementById("crmtype").value;

	var shortcode = '';
	if(onAction == 'onEditShortCode')
	{
		shortcode = jQuery('#shortcode').val();
	}
	if(module != "--Select--" && crmtype != "--Select--") {	
		document.getElementById('loading-image').style.display = "block";
		jQuery.ajax({
			type: 'POST',
			url: leads_builder_ajax_object.url,
			data: {
				'action'     : 'adminAllActionsPRO',
				'doaction'   : 'GetTemporaryFields',
				'siteurl'    : siteurl,
				'module'     : module,
				'crmtype'    : crmtype,
				'option'     : option,
				'onAction'   : onAction,
				'shortcode'  : shortcode,
				'securekey'  : leads_builder_ajax_object.nonce,
			},
			success:function(data) {
				jQuery("#fieldtable").html(data);
				getassignedToUserPRO( module , crmtype , siteurl, option, onAction, shortcode );
				document.getElementById('loading-image').style.display = "none";
				location.reload();
			},
			error: function(errorThrown){

			}
		});
	}
}



function getassignedToUserPRO( module , crmtype , siteurl, option, onAction, shortcode )
{
	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: {
			'action'     : 'adminAllActionsPRO',
			'doaction'   : 'GetAssignedToUser',
			'siteurl'    : siteurl,
			'module'     : module,
			'crmtype'    : crmtype,
			'option'     : option,
			'onAction'   : onAction,
			'shortcode'  : shortcode,
			'securekey'  : leads_builder_ajax_object.nonce,
		},
		success:function(data) {
			jQuery("#assignedto_td").html(data);
		},
		error: function(errorThrown){

		}
	});
}

/*Form building module JS*/
function enableredirecturl(id) {
	if(document.getElementById("enableurlredirection").checked == true) {
		document.getElementById("redirecturl").disabled = false;
	} else {
		document.getElementById("redirecturl").disabled = true;
	}
}

function enablesmackemail(id) {
	var smack_email_condition = jQuery( "#emailcondition" ).val();
	if( smack_email_condition == "none" )
	{
		jQuery("#email").prop( 'disabled' , true );	
	}
	else
	{
		jQuery("#email").prop( 'disabled' , false );
	}
}

function convert_lead( id )
{
	if(document.getElementById("ecom_convert_lead").checked == true) {
		jQuery( "#ecom_convert_lead" ).val( 'on' );
		var convert_lead = 'on';
	} else {
		jQuery( "#ecom_convert_lead" ).val('off');
		var convert_lead = 'off';
	}
	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: {
			'action'     : 'save_convert_lead',
			'convert_lead'  : convert_lead,
			'securekey'     : leads_builder_ajax_object.nonce,
		},
		success:function(data) {
		},
		error: function(errorThrown){

		}
	});

}

function enablesmackTFA(id) {
	if(document.getElementById("TFA_check").checked == true) {
		document.getElementById("TFA_authkey").disabled = false;
		jQuery( "#TFA_check" ).val( 'on' );
	} else {
		document.getElementById("TFA_authkey").disabled = true;
		jQuery( "#TFA_check" ).val('off');
	}
}

function debugmod(id) {
	if(document.getElementById("debugmode").checked == true) {
		jQuery( "#debugmode" ).val('on');
	} else {
		jQuery( "#debugmode" ).val('off');
	}
}

function save_freshsales_settings ( domainURL, appToken ) {

}

function map_sync_user_view() {
	var user_field = jQuery( "#userfield").val();
	var count = jQuery( "#totaluserfields" ).val();
}


function save_salesforece_settings (key, val) {
	jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: {
			'action'  : 'saveSFSettings',
			'key'     : key,
			'value'  : val,
			'securekey' : leads_builder_ajax_object.nonce,
		},
		success:function(data) {
			if( key == 'secret')
			{
				// swal( "Success!", 'Consumer Secret Saved Successfully!' , 'success');
				// location.reload();
			}else if( key == 'key'){
				// swal( "Sucess!" , 'Consumer Key saved Successfully!' , 'success');
				// location.reload();
			}
		},
		error: function(errorThrown){

		}
	});
}

function save_zoho_settings (key, val) {
	jQuery.ajax({
	type: 'POST',
	url: leads_builder_ajax_object.url,
	data: {
		'action'  : 'saveZohoSettings',
		'key'     : key,
		'value'  : val,
		'securekey' : leads_builder_ajax_object.nonce,
	},
	success:function(data) {
		if( key == 'secret')
		{
		
		}else if( key == 'key'){
		
		}

		},
		error: function(errorThrown){

		}
	});
}

function redirectZohoCRM(){
    jQuery.ajax({
		type: 'POST',
		url: leads_builder_ajax_object.url,
		data: {
			'action'  : 'zohoCRMRedirect',
			'securekey' : leads_builder_ajax_object.nonce,
		},
		success:function(data) {
        	location.href = JSON.parse(data);
        },
        error: function(errorThrown){
        }
    });
}

// enable captcha code
jQuery(function(){

	jQuery('#smack_recaptcha_yes').on('ifChecked', function(){
		jQuery('.leads-captcha,#recaptcha_private_key,#recaptcha_public_key').slideDown('slow').css('display','block');
	});

	jQuery('#smack_recaptcha_yes').on('ifUnchecked', function(){
		jQuery('.leads-captcha').slideUp('slow');
		});
});

