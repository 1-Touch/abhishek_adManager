<?php

	require_once('configuration.php');

	// Core Classes
	use FacebookAds\Object\AdAccount;
	use FacebookAds\Api;
	use FacebookAds\Logger\CurlLogger;

	use FacebookAds\Object\User;
	use FacebookAds\Object\Campaign;
	// use FacebookAds\Object\AdAccountActivity;


	/* 
		- Get the adaccounts of user using token with graph facebook, By using file_get_contents() function. 
		- Code Block Start
	*/
	$requestUrl = @file_get_contents("https://graph.facebook.com/v6.0/me/adaccounts?access_token=" . $_SESSION['fb_access_token']);
	$urlResponse = @json_decode($requestUrl, true);

	if(isset($urlResponse) && !empty($urlResponse) && isset($urlResponse['data']) && sizeof($urlResponse['data']) > 0)
	{
		foreach ($urlResponse['data'] as $keyUR => $valueUR) 
		{
			$account_id = $valueUR['id'];
		}
	}
	/* - Code Block End */

	// Initialize a new Session and instantiate an API object and assign to variable $api
	$api = Api::init(
		$app_id, // App ID
		$app_secret, // App ID
		$_SESSION['fb_access_token'] // Your user access token
	);

	$api->setLogger(new CurlLogger());

	$fields = array(
	);
	$params = array(
	);
	
	$getAdAccounts = (new User($_SESSION['id']))->getAdAccounts(
	  $fields,
	  $params
	)->getResponse()->getContent();

	if(isset($getAdAccounts) && !empty($getAdAccounts) && isset($getAdAccounts['data']) && sizeof($getAdAccounts['data']) > 0)
	{
		foreach ($getAdAccounts['data'] as $keyUR => $valueUR) 
		{
			$account_id = $valueUR['id'];
		}
	}

	/*$getAdAccounts = json_encode((new User($_SESSION['id']))->getAdAccounts(
	  $fields,
	  $params
	)->getResponse()->getContent(), JSON_PRETTY_PRINT);*/


	// Create Campaign Process Start
	/*$createCampaignFields = array(
	);
	$createCampaignParams = array(
		'name' => 'sdk campaign',
		'objective' => 'LINK_CLICKS', // APP_INSTALLS, BRAND_AWARENESS, CONVERSIONS, EVENT_RESPONSES, LEAD_GENERATION, LINK_CLICKS, LOCAL_AWARENESS, MESSAGES, OFFER_CLAIMS, PAGE_LIKES, POST_ENGAGEMENT, PRODUCT_CATALOG_SALES, REACH, VIDEO_VIEWS
		'status' => 'PAUSED',
		'special_ad_category' => 'NONE'//must be capital
	);

	echo json_encode((new AdAccount($account_id))->createCampaign(
		$createCampaignFields,
		$createCampaignParams
	)->exportAllData(), JSON_PRETTY_PRINT); die();*/

	// Create Campaign Process End


	// Campaign List Process Start
	$listCampaignFields = array(
		'name',
		'objective',
	);
	
	$listCampaignParams = array(
	  	'effective_status' => array('ACTIVE','PAUSED'),
	);
	echo json_encode((new AdAccount($account_id))->getCampaigns(
		$listCampaignFields,
		$listCampaignParams
	)->getResponse()->getContent(), JSON_PRETTY_PRINT);
	// Campaign List Process End