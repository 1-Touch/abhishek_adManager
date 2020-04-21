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
	  	$response = $fb->get('/me?fields=id,email,birthday,gender', $accessToken);
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

	$result = $response->getDecodedBody();
	echo '<pre>';
	print_r($result);
	echo '</pre>';
	$_SESSION['id'] = $result['id'];

	echo "<br>";
	$marketingUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . basename(__DIR__) . '/marketing_details.php';
	$adManagerUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . basename(__DIR__) . '/campaigns.php';
	echo 'If you want to see your marketing details. Please click here    <a href="' . htmlspecialchars($marketingUrl) . '">Marketing Details!</a>';

	