<?php
	
	require_once('configuration.php');

	$helper = $fb->getRedirectLoginHelper();

	try 
	{
		$accessToken = $helper->getAccessToken();
	} 
	catch(Facebook\Exceptions\FacebookResponseException $e) 
	{
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} 
	catch(Facebook\Exceptions\FacebookSDKException $e) 
	{
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	if (! isset($accessToken)) 
	{
		if ($helper->getError()) 
		{
			header('HTTP/1.0 401 Unauthorized');
			echo "Error: " . $helper->getError() . "\n";
			echo "Error Code: " . $helper->getErrorCode() . "\n";
			echo "Error Reason: " . $helper->getErrorReason() . "\n";
			echo "Error Description: " . $helper->getErrorDescription() . "\n";
		} 
		else 
		{
			header('HTTP/1.0 400 Bad Request');
			echo 'Bad request';
		}
		exit;
	}

	// The OAuth 2.0 client handler helps us manage access tokens
	$oAuth2Client = $fb->getOAuth2Client();

	// Get the access token metadata from /debug_token
	$tokenMetadata = $oAuth2Client->debugToken($accessToken);

	// Validation (these will throw FacebookSDKException's when they fail)
	$tokenMetadata->validateAppId($app_id);

	//$tokenMetadata->validateUserId('123');
	$tokenMetadata->validateExpiration();

	if (! $accessToken->isLongLived()) 
	{
		// Exchanges a short-lived access token for a long-lived one
		try 
		{
			$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		} 
		catch (Facebook\Exceptions\FacebookSDKException $e) 
		{
			echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
			exit;
		}

		// echo '<h3>Long-lived</h3>';
		// var_dump($accessToken->getValue());
	}

	$_SESSION['fb_access_token'] = (string) $accessToken;

	try 
	{
		// Get your UserNode object, replace {access-token} with your token
	  	$response = $fb->get('/me/accounts?fields=about,single_line_address,contact_address,category_list,fan_count,access_token,tasks,can_post,global_brand_page_name,cover,current_location,picture,username,description,location,name,phone,website,emails,has_whatsapp_business_number,has_whatsapp_number,hometown,hours,is_published,price_range,is_always_open', $accessToken);
	} 
	catch(\Facebook\Exceptions\FacebookResponseException $e) 
	{
	    // Returns Graph API errors when they occur
	  	echo 'Graph returned an error: ' . $e->getMessage();
	  	exit;
	} 
	catch(\Facebook\Exceptions\FacebookSDKException $e) 
	{
	    // Returns SDK errors when validation fails or other local issues
	  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  	exit;
	}

	echo '<pre>';
	print_r($response->getDecodedBody());
	echo '</pre>';
	die();
	$me = $response->getGraphUser();

	echo '<pre>';
	print_r($me); 
	echo '</pre>';
	die();
?>