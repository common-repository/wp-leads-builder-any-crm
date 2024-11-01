<?php
/**
 * WP Leads Builder For Any CRM.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class joforceFunctions
{
	/**
	 * Joforce Username
	 */
	public $username;

	/**
	 * Joforce Password
	 */
	public $password;

	/**
	 * Joforce app url
	 */
	public $url;

	/**
	 * Joforce API end point
	 */
	public $end_point = 'api/v1';

	/**
	 * Joforce auth token
	 */
	public $token;

	public $result_emails;

	public $result_ids;

	public $result_products;

	/**
	 * Joforce Constructor
	 */
	public function __construct()
	{
		global $lb_crmm;
		$WPCapture_includes_helper_Obj = new WPCapture_includes_helper_PRO();
		$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$SettingsConfig = get_option("wp_{$activateplugin}_settings");
		$crm_type = sanitize_text_field($_REQUEST['crmtype']);
		if(isset($_REQUEST['crmtype']))	{
			$SettingsConfig = get_option("wp_{$crm_type}_settings");
		} 
		else	{
			$SettingsConfig = get_option("wp_{$activateplugin}_settings");
		}
		$this->username = isset($SettingsConfig['username']) ? $SettingsConfig['username'] : '';
		$this->password = isset($SettingsConfig['password']) ? $SettingsConfig['password'] : '';
		$this->url = isset ($SettingsConfig['url']) ? $SettingsConfig['url'] : '';
		$this->token = isset($SettingsConfig['token']) ? $SettingsConfig['token'] : '';
		$lb_crmm->setConfigurationDetails($SettingsConfig);
	}

	/**
	 * Login to Joforce
	 * 
	 * @return array $response
	 */
	public function login()
	{
		$params = array('username' => $this->username, 'password' => $this->password);
		$url = $this->url . '/' . $this->end_point . '/authorize';
		$response = $this->call($url, $params, 'POST');
		return $response;
	}

	/**
	 * Return Joforce module fields
	 * 
	 * @return array $config_fields
	 */
	public function getCrmFields($module)
	{
		$url = $this->url . '/' . $this->end_point . '/' . $module . '/' . 'fields';
		$recordInfo = $this->call($url, array(), 'GET');
		// If token expired, try to login again
		if(isset($recordInfo['success']) && $recordInfo['success'] != true && $recordInfo['code'] == 401)	{
			$this->login();
			$recordInfo = $this->call($url, array(), 'GET');
		}

		$config_fields = array();
		if($recordInfo)
		{
			$j = 0;
			$count=count($recordInfo['fields']);
			for($i = 0; $i<$count; $i++)
			{
				// If type is not set for field, skip that field.
				if(!isset($recordInfo['fields'][$i]['type']['name']))	{
					continue;
				}

				if($recordInfo['fields'][$i]['type']['name'] == 'reference')	{
					//
				}
				elseif($recordInfo['fields'][$i]['name'] == 'modifiedby' || $recordInfo['fields'][$i]['name'] == 'assigned_user_id' )	{
					//
				}
				else{
					$config_fields['fields'][$j] = $recordInfo['fields'][$i];
					$config_fields['fields'][$j]['order'] = $j;
					$config_fields['fields'][$j]['publish'] = 1;
					$config_fields['fields'][$j]['display_label'] = $recordInfo['fields'][$i]['label'];
					if($recordInfo['fields'][$i]['mandatory'] == 1)
					{
						$config_fields['fields'][$j]['wp_mandatory'] = 1;
						$config_fields['fields'][$j]['mandatory'] = 2;
					}
					else
					{
						$config_fields['fields'][$j]['wp_mandatory'] = 0;
					}
					$j++;
				}
			}
			$config_fields['check_duplicate'] = 0;
			$config_fields['isWidget'] = 0;
			$config_fields['update_record'] = 0;
			$users_list = $this->getUsersList();
			$config_fields['assignedto'] = $users_list['id'][0];
			$config_fields['module'] = $module;
		}
		return $config_fields;
	}

	/**
	 * Return users from Joforce
	 * 
	 * @return array $user_details
	 */
	public function getUsersList()
	{
		$page = 1;
		$user_details=[];
		$users_list = array();
		do {
			$url = $this->url . '/' . $this->end_point . '/Users/list/' . $page;
			$users_list = $this->call($url, array(), 'GET');
			// If token expired, try to login again
			if (isset($users_list['success']) && $users_list['success'] != true && $users_list['code'] == 401) {
				$this->login();
				$recordInfo = $this->call($url, array(), 'GET');
			}

			if ($users_list) {
				if (!empty($users_list) && is_array($users_list) || is_object($users_list)){
					$users_list_record =  $users_list['records'];
					foreach ($users_list_record as $record) {
						$user_details['user_name'][] = $record['user_name'];
						$user_details['id'][] = $record['id'];
						$user_details['first_name'][] = $record['first_name'];
						$user_details['last_name'][] = $record['last_name'];
					}
				}
			}
			$page = $page + 1;
		} while ($users_list['moreRecords'] === true);

		return $user_details;
	}

	/**
	 * Generate dropdown field using Users list
	 * 
	 * @return string $html
	 */
	public function getUsersListHtml($shortcode = "")
	{
		$HelperObj = new WPCapture_includes_helper_PRO();
		// $module = $HelperObj->Module;
		// $moduleslug = $HelperObj->ModuleSlug;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;
		$formObj = new CaptureData();
		if (isset($shortcode) && ($shortcode != "")) {
			$config_fields = $formObj->getFormSettings($shortcode);  // Get form settings
		}

		$users_list = get_option('crm_users');
		$users_list = $users_list[$activatedplugin];
		$html = "";
		$html = '<select name="assignedto" id="assignedto" style="min-width:69px;">';
		$content_option = "";
		if (isset($users_list['user_name']))
		$count=count($users_list['user_name']);
			for ($i = 0; $i < $count; $i++) {
				$content_option .= "<option id='{$users_list['id'][$i]}' value='{$users_list['id'][$i]}'";
				if ($users_list['id'][$i] == $config_fields->assigned_to) {
					$content_option .= " selected";
				}
				$content_option .= ">{$users_list['first_name'][$i]} {$users_list['last_name'][$i]}</option>";
			}

		$content_option .= "<option id='owner_rr' value='Round Robin'";
		if ($config_fields->assigned_to == 'Round Robin') {
			$content_option .= "selected";
		}
		$content_option .= "> Round Robin </option>";
		$html .= $content_option;
		$html .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
		return $html;
	}

	/**
	 * Return users list of Joforce
	 * 
	 * @return array $user_list_array
	 */
	public function getAssignedToList()
	{
		$user_list_array=[];
		$users_list = $this->getUsersList();
		$count=count($users_list['user_name']);
		for($i = 0; $i < $count ; $i++)
		{
			$user_list_array[$users_list['id'][$i]] = $users_list['first_name'][$i] ." ". $users_list['last_name'][$i];
		}
		return $user_list_array;
	}

	/**
	 * Assigned to field name of Joforce
	 */
	public function assignedToFieldId()
	{
		return "assigned_user_id";
	}

	/**
	 * Map user capture fields
	 * 
	 * @param string $user_firstname
	 * @param string $user_lastname
	 * @param string $user_email
	 * @return array $post
	 */
	public function mapUserCaptureFields( $user_firstname , $user_lastname , $user_email )
	{
		$post = array();
		$post['firstname'] = $user_firstname;
		$post['lastname'] = $user_lastname;
		$post[$this->duplicateCheckEmailField()] = $user_email;
		return $post;
	}

	/**
	 * Create record when user captured
	 */
	public function createRecordOnUserCapture( $module , $module_fields )
	{
		return $this->createRecord( $module , $module_fields );
	}

	/**
	 * Create a new record to Joforce
	 * 
	 * @param string $module
	 * @param array $module_fields
	 * @return array $data
	 */
	public function createRecord($module, $module_fields)
	{
		$data=[];
		$url = $this->url . '/' . $this->end_point . '/' . $module;
		$response = $this->call($url, $module_fields, 'POST');
		// If token expired, try to login again
		if (isset($response['success']) && $response['success'] != true && $response['code'] == 401) {
			$this->login();
			$response = $this->call($url, $module_fields, 'POST');
		}

		//if(isset($response['code']) && $response['code'] != 200)	{
		if(!empty($response['createdtime']))	{
			$data['result'] = "failure";
			$data['failure'] = 1;
			$data['reason'] = "failed adding entry";
		} 
		else {
			$data['result'] = "success";
			$data['failure'] = 0;
		}
		return $data;
	}

	/**
	 * Update Joforce record 
	 * 
	 * @param string $module
	 * @param array $module_fields
	 * @param id $ids_present
	 * @return array $data
	 */
	public function updateRecord( $module , $module_fields , $ids_present )
	{
		$data=[];
		$url = $this->url . '/' . $this->end_point . '/' . $module . '/' . $ids_present;
		$response = $this->call($url, $module_fields, 'PUT');
		// If token expired, try to login again
		if (isset($response['success']) && $response['success'] != true && $response['code'] == 401) {
			$this->login();
			$response = $this->call($url, $module_fields, 'PUT');
		}

		if(isset($response['code']) && $response['code'] != 200)	{
			$data['result'] = "failure";
			$data['failure'] = 1;
			$data['reason'] = "failed updating entry";
		} 
		else {
			$data['result'] = "success";
			$data['failure'] = 0;
		}
		return $data;
	}

	/**
	 * Check email present in the module
	 * 
	 * @param string $module
	 * @param string $email
	 * @return boolean
	 */
	public function checkEmailPresent( $module , $email )
	{
		$result_emails = array();
		$result_ids = array();
		$url = $this->url . '/' . $this->end_point . '/' . $module . '/search/email/' . $email;
		$response = $this->call($url, array(), 'GET');
		// If token expired, try to login again
		if (isset($response['success']) && $response['success'] != true && $response['code'] == 401) {
			$this->login();
			$response = $this->call($url, array(), 'GET');
		}

		if(isset($response['records']) && count($response['records']) > 0)	{
			foreach($response['records'] as $record)	{
				$result_emails[] = $record['email'];
				$result_ids[] = $record['id'];
			}
			$this->result_emails = $result_emails;
			$this->result_ids = $result_ids;
			return true;
		}
		return false;
	}

	/**
	 * Joforce email field
	 */
	public function duplicateCheckEmailField()
	{
		return 'email';
	}

	/**
	 * Call to CRM
	 * 
	 * @param string $url
	 * @param array $params
	 * @param string $method
	 */
	public function call($url, $params, $method)
	{
		if($method == 'PUT')	{
			$post_params = null;
			foreach($params as $key => $value)	{ 
				$post_params .= $key.'='.$value.'&'; 
			}
			rtrim($post_params, '&');
		}
		else	{
			$post_params = $params;
		}

		$headers = array( 'Authorization' => 'Bearer '. $this->token,
							'Cache-Control' =>  'no-cache'
								//'Content-Type' => 'application/json'
						);
			
		$args = array(
			'method' => $method,
			'sslverify' => false,
			'body' => $post_params,
			'headers' => $headers
			);
			
		$result = wp_remote_post($url, $args ) ;
		$response = wp_remote_retrieve_body($result);
		$http_code = wp_remote_retrieve_response_code($result);
		$result_array = json_decode($response,TRUE);
		return $result_array;
	}
}
