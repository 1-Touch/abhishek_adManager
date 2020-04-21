<?php
require __DIR__ . '/../vendor/autoload.php';

// If account is public you can query Instagram without auth

$instagram = new \InstagramScraper\Instagram();
$medias = $instagram->getMedias('prateektekwani', 25);

// Let's look at $media
$media = $medias[0];

echo "Media info:\n"."</br>";
echo "Id: {$media->getId()}\n"."</br>";
echo "Shortcode: {$media->getShortCode()}\n"."</br>";
echo "Created at: {$media->getCreatedTime()}\n"."</br>";
echo "Caption: {$media->getCaption()}\n"."</br>";
echo "Number of comments: {$media->getCommentsCount()}"."</br>";
echo "Number of likes: {$media->getLikesCount()}"."</br>";
echo "Get link: {$media->getLink()}"."</br>";
echo "High resolution image: {$media->getImageHighResolutionUrl()}"."</br>";
echo "Media type (video or image): {$media->getType()}"."</br>";
$account = $media->getOwner()."</br>";
echo "Account info:\n";
echo "Id: {$account->getId()}\n"."</br>";
echo "Username: {$account->getUsername()}\n"."</br>";
echo "Full name: {$account->getFullName()}\n"."</br>";
echo "Profile pic url: {$account->getProfilePicUrl()}\n"."</br>";


// If account private you should be subscribed and after auth it will be available
// $instagram = \InstagramScraper\Instagram::withCredentials('username', 'password', 'path/to/cache/folder');
// $instagram->login();
// $medias = $instagram->getMedias('private_account', 100);
