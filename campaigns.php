<?php
require_once('configuration.php');

use FacebookAds\Object\AdAccount;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;

$api = Api::init(
    $app_id, // App ID
    $app_secret, // App ID
    $_SESSION['fb_access_token'] // Your user access token
    );
    
$api->setLogger(new CurlLogger());
$fields = array(
    'name',
    'objective',
    'status'
  );
  $params = array(
    'effective_status' => array('ACTIVE','PAUSED'),
  );
  $response = (new AdAccount($account_id))->getCampaigns(
    $fields,
    $params
  )->getResponse()->getContent();
  $campaigns = $response['data'];  
  $adManagerUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . basename(__DIR__) . '/campaigns.php';
  
  //echo $adManagerUrl;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Campaigns</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
  <script src="./custom/custom.js"></script>
  <link rel="stylesheet" href="./custom/css/custom.css">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-md-9">
      <h3>Campaigns</h3>
      <table class="table table-bordered">
        <thead class="thead-light">
          <th>No.</th><th>NAME</th><th>STATUS</th><th>CAMPAIGN'S OBJECTIVE</th><th>IMPRESSIONS</th><th>SPENT</th>
      </thead>
        <?php
        $i = 1;
        foreach($campaigns as $campaign) {
          echo "<tr><td>".$i."</td><td>".$campaign['name']."</td><td>".$campaign['status']."</td><td>".$campaign['objective']."</td><td>0</td><td>0</td></tr>";
          $i++;
        }
        ?>
      </table>
    </div>
  </div>
  <div class="row">
      <div class="col-md-9">
        <h3>Create Campaigns</h3>
        <?php
          try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get(
              '/{page-id}/likes',
              '{access-token}'
            );
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
          $graphNode = $response->getGraphNode();
        ?>
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Create</button>
      </div>
  </div>
</div>
<!--modal to create campaigns-->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
      <form action="" name="campaign-form" id="campaign-form">
        <div class="form-group">
          <label for="campaign">Campaign Name*:</label>
          <input type="text" class="form-control" id="campaign" name="campaign" value="">
        </div>
        <div class="form-group">
          <label for="campaign">CAMPAIGN'S OBJECTIVE</label>
          <select class="form-control" name="objective" id="objective">
            <option value="LINK_CLICKS">Link Clicks</option>
            <option value="POST_ENGAGEMENT">Post Engagement</option>
          </select>
        </div>
        <div class="form-group">
          <label for="campaign">CAMPAIGN'S STATUS</label>
          <select class="form-control" name="status" id="status">
            <option value="ACTIVE">ACTIVE</option>
            <option value="PAUSED">PAUSED</option>
          </select>
        </div>
        <div class="form-group">
          <label for="campaign">SPECIAL AD CATEGORY</label>
          <select class="form-control" name="category" id="category">
            <option value="HOUSING">HOUSING</option>
            <option value="CREDIT">CREDIT</option>
            <option value="EMPLOYMENT">EMPLOYMENT</option>
            <option value="NONE">NONE</option>
          </select>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <div class="alert " style="margin-top:10px;display:none;" id="campaign-response">
        
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id='loadingmessage' class="loading" style='display:none;'>
  <img class="loading-image" src='./custom/images/loading.gif' alt="Loading.."/>
</div>
</body>
</html>