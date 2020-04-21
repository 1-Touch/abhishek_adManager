<?php

	require_once('configuration.php');

	/*if(isset($_SESSION['fb_access_token']))
	{
		header('Location: profile.php');
	}*/

	$helper = $fb->getRedirectLoginHelper();

	$permissions = [
		'email',
		'user_friends',
		'user_birthday',
		'user_likes',
		'manage_pages',
		'ads_management',
		'ads_read'
		// 'user_gender',
		// 'user_posts'
	]; // Optional permissions

	$callBackUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'user_profile.php';

	$loginUrl = $helper->getLoginUrl($callBackUrl, $permissions);

	echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

?>