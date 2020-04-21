<?php

	require_once('configuration.php');

	/*if(isset($_SESSION['fb_access_token']))
	{
		header('Location: profile.php');
	}*/

	$helper = $fb->getRedirectLoginHelper();

	$permissions = [
		'manage_pages',
		'pages_show_list',
		'business_management'
	]; // Optional permissions

	$callBackUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . basename(__DIR__) . '/page_details.php';

	$loginUrl = $helper->getLoginUrl($callBackUrl, $permissions);

	echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

?>