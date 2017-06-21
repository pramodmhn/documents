<?php

global $frm_update;
$frm_update  = new FrmUpdatesController();
$frm_vars['pro_is_authorized'] = $frm_update->pro_is_authorized();

// load the license form
if ( FrmAppHelper::is_admin_page('formidable-settings') ) {
    add_action('frm_before_settings', 'FrmProSettingsController::license_box', 1);
}


/* Customize by Swift Digital - Start */

function dumpvar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

function prepareData() {
	$data = array();
	$item_meta = $_REQUEST['item_meta'];
	dumpvar($item_meta);
	$data['oid'] = '00D90000000ylFF';
	$data['retURL'] = 'http://internationalcyclingexecutives.com/member-application-3/';
	
	$data['first_name']  = $item_meta[87];
	$data['last_name']   = $item_meta[88];
	$data['email']       = $item_meta[89];
	$data['Email_Secondary__c']	=  	$item_meta[90];     
	$data['mobile']      = $item_meta[91];
	$data['Gender__c']	=  	$item_meta[92];
	$data['Country__c']     = $item_meta[94];
	$data['State__c']       = $item_meta[95];   	
	$data['City__c']        = $item_meta[97];	
	$data['Post_Code__c']         = $item_meta[96];  
	$data['Ice_Organisation__c']     = $item_meta[98];	
	$data['Size_of_organisation__c'] 	=	$item_meta[101]; 
	$data['Job_Title__c']       = $item_meta[102];
	$data['Function__c']				=	$item_meta[117];
	$data['Function_Other__c']				=	$item_meta[other][117];
	$data['Industry_Sector__c']						=	$item_meta[118];
	$data['Company_Board_Activity__c']					=	$item_meta[105];
	$data['Linkedin_Member__c']						=	$item_meta[106];
   	$data['Linkedin_Public_Profile__c']					=	$item_meta[107];
    $data['Twitter_Account__c']						=	$item_meta[108];
   	$data['Twitter_Handle__c']						=	$item_meta[109];
	foreach ($item_meta[110] as $evLoc){
	$data['Event_Location__c'] 		= $evLoc;
	}
  // $data['Event_Location__c']						=	$item_meta[110];

  // $data['Event_Location_Other__c']					=	$item_meta[other][110][other_11];
   $data['Event_attend_duration__c']				=	$item_meta[119];
   $data['Primary_reason_for_attending_events__c']						=	$item_meta[111];
   $data['Primary_reason_for_attending_event_Other__c']						=	$item_meta[other][111];
   $data['Referral_Source__c']						=	$item_meta[112];
   $data['Referral_Source_Other__c']						=	$item_meta[other][112];														
   $data['Referred_by__c']							=	$item_meta[113];
   $data['ICE_terms_and_conditions__c'] = $item_meta[114][0];
   $data['News_events_and_information_updates__c'] = $item_meta[116];
   
   	$data['lead_source'] = 'ICE MEMBER APPLICATION FORM';
    

	$data['debug']       = 1;
	$data['debugEmail']  = 'pramod.m@swiftdigital.com.au';
	return $data;
}


function curlDataSend($url, $data) {
	ob_start();
	$ch = curl_init();
	$header = "Content-type: text/xml;charset=UTF-8";

	foreach ($data as $key => $value) {

		//Set array element for each POST variable (ie. first_name=Arsham)
		if(is_string($value))
		$kv[] = stripslashes($key)."=".stripslashes($value);
		echo "<pre>";
		print_r($kv);
		echo "</pre>";
	}

	$query_string = join("&", $kv);

	curl_setopt($ch, CURLOPT_URL, $url);

	//curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	curl_setopt($ch, CURLOPT_POST, count($kv));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

	$response = curl_exec($ch);
	if(curl_errno($ch)){
		print curl_error($ch);
	}
	curl_close($ch);
	ob_end_clean();
}

add_action('frm_after_create_entry', 'sendToSalesForce');
function sendToSalesForce(){
	if(($_REQUEST['frm_action'] == 'create') && ($_REQUEST['form_id'] == 7) && ($_REQUEST['form_key'] == 'axtlql')){
		$url = "https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8";
		$data = prepareData();
		@curlDataSend($url, $data);
	}
}
/* Customize by Swift Digital - End */


if ( ! $frm_vars['pro_is_authorized'] ) {
    return;
}

$frm_vars['next_page'] = $frm_vars['prev_page'] = array();
$frm_vars['pro_is_installed'] = 'deprecated';
add_filter('frm_pro_installed', '__return_true');

add_filter('frm_load_controllers', 'frmpro_load_controllers');
function frmpro_load_controllers( $controllers ) {
    $controllers[] = 'FrmProHooksController';
    return $controllers;
}