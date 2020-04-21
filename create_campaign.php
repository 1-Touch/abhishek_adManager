<?php
include_once('configuration.php');

use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;

$api = Api::init($app_id, $app_secret, $_SESSION['fb_access_token']);
$api->setLogger(new CurlLogger());

$fields = array(
);

$campaign = $_POST['campaign'];
$objective = $_POST['objective'];
$status = $_POST['status'];
$category = $_POST['category'];

$params = array(
  'name' => $campaign,
  'objective' => $objective,
  'status' => $status,
  'special_ad_category' => $category
);

echo json_encode((new AdAccount($account_id))->createCampaign(
  $fields,
  $params
)->exportAllData(), JSON_PRETTY_PRINT);


