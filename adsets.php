<?php
require_once('configuration.php');

use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\User;

$requestUrl = @file_get_contents("https://graph.facebook.com/v6.0/me/adaccounts?access_token=" . $_SESSION['fb_access_token']);
$urlResponse = @json_decode($requestUrl, true);

if(isset($urlResponse) && !empty($urlResponse) && isset($urlResponse['data']) && sizeof($urlResponse['data']) > 0)
{
    foreach ($urlResponse['data'] as $keyUR => $valueUR) 
    {
        $account_id = $valueUR['id'];
    }
}

$api = Api::init(
    $app_id, // App ID
    $app_secret, // App ID
    $_SESSION['fb_access_token'] // Your user access token
    );
    
$api->setLogger(new CurlLogger());
$fields = array(
    'name',
  'start_time',
  'end_time',
  'daily_budget',
  'lifetime_budget',
  'targeting',
  'billing_event',
  'optimization_goal',
  'bid_amount',
  );
  $params = array(
    'effective_status' => array('ACTIVE','PAUSED'),
  );
  echo "<pre>".json_encode((new Campaign($account_id))->getAdSets(
    $fields,
    $params
  )->getResponse()->getContent(), JSON_PRETTY_PRINT);
?>