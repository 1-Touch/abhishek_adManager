<?php
require __DIR__ . '/../vendor/autoload.php';
use Phpfastcache\Helper\Psr16Adapter;
$instagram = \InstagramScraper\Instagram::withCredentials('vikaspatel2249', 'Shrivastav@1', new Psr16Adapter('Files'));
$instagram->login();


//$instagram = new \InstagramScraper\Instagram();
sleep(2); // Delay to mimic user

$username = 'vickykaushal09';
$followers = [];
$account = $instagram->getAccount($username);
sleep(1);
$followers = $instagram->getFollowing($account->getId(), 1000, 100, true); // Get 1000 followings of 'kevin', 100 a time with random delay between requests
echo '<pre>' . json_encode($followers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</pre>';