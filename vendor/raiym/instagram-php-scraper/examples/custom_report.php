<?php
require __DIR__ . '/../vendor/autoload.php';

function get_hashtags($string) {

    preg_match_all('/(?:#)([\p{L}\p{N}_](?:(?:[\p{L}\p{N}_]|(?:\.(?!\.))){0,28}(?:[\p{L}\p{N}_]))?)/u', $string, $array);


    return $array[1];

}

function get_mentions($string) {

    preg_match_all('/@([\w\d][\w\d\.\_]+[\w\d])/', $string, $array);


    return $array[1];

}

$instagram = new \InstagramScraper\Instagram();


$user="vickykaushal09";
$source_account_data = $instagram->getAccount($user);

$source_account_new = new StdClass();
$source_account_new->instagram_id = $source_account_data->getId();
$source_account_new->username = $source_account_data->getUsername();
$source_account_new->full_name = $source_account_data->getFullName() != '' ? $source_account_data->getFullName() : $source_account_new->username;
$source_account_new->description = $source_account_data->getBiography();
$source_account_new->website = $source_account_data->getExternalUrl();
$source_account_new->followers = $source_account_data->getFollowedByCount();
$source_account_new->following = $source_account_data->getFollowsCount();
$source_account_new->uploads = $source_account_data->getMediaCount();
$source_account_new->profile_picture_url = $source_account_data->getProfilePicUrl();
$source_account_new->is_private = (int)$source_account_data->isPrivate();
$source_account_new->is_verified = (int) $source_account_data->isVerified();

$media_response = $instagram->getPaginateMedias($user, '', $source_account_data);

if ($media_response && !empty($media_response)) {
    foreach ($media_response['medias'] as $media) {

        $likes_array[$media->getShortCode()] = $media->getLikesCount();
        $comments_array[$media->getShortCode()] = $media->getCommentsCount();
        $engagement_rate_array[$media->getShortCode()] = nr(($media->getLikesCount() + $media->getCommentsCount()) / $source_account_new->followers * 100, 2);

        $hashtags = get_hashtags($media->getCaption());

        foreach ($hashtags as $hashtag) {
            if (!isset($hashtags_array[$hashtag])) {
                $hashtags_array[$hashtag] = 1;
            } else {
                $hashtags_array[$hashtag]++;
            }
        }

        $mentions = get_mentions($media->getCaption());

        foreach ($mentions as $mention) {
            if (!isset($mentions_array[$mention])) {
                $mentions_array[$mention] = 1;
            } else {
                $mentions_array[$mention]++;
            }
        }

        /* End if needed */
        //if (count($likes_array) >= $settings->instagram_calculator_media_count) break;
    }
    $details['total_likes'] = array_sum($likes_array);
    $details['total_comments'] = array_sum($comments_array);
    $details['average_comments'] = count($likes_array) > 0 ? $details['total_comments'] / count($comments_array) : 0;
    $details['average_likes'] = count($likes_array) > 0 ? $details['total_likes'] / count($likes_array) : 0;
    //$source_account_new->average_engagement_rate = count($likes_array) > 0 ? number_format(array_sum($engagement_rate_array) / count($engagement_rate_array), 2) : 0;
    arsort($hashtags_array);
    arsort($mentions_array);
    $top_posts_array = array_slice($engagement_rate_array, 0, 3);
    $top_hashtags_array = array_slice($hashtags_array, 0, 15);
    $top_mentions_array = array_slice($mentions_array, 0, 15);

    $details['top_hashtags'] = $top_hashtags_array;
    $details['top_mentions'] = $top_mentions_array;
    $details['top_posts'] = $top_posts_array;
    $source_account_new->details = $details;
}


//--------------------------------------------------


function nr($number, $decimals = 0, $extra = false) {
    global $language;

    if($extra) {

        if(!is_array($extra) || (is_array($extra) && in_array('B', $extra))) {

            if($number > 999999999) {
                return floor($number / 1000000000) . 'B';
            }

        }

        if(!is_array($extra) || (is_array($extra) && in_array('M', $extra))) {

            if($number > 999999) {
                return floor($number / 1000000) . 'M';
            }

        }

        if(!is_array($extra) || (is_array($extra) && in_array('K', $extra))) {

            if($number > 999) {
                return floor($number / 1000) . 'K';
            }

        }

    }

    if($number == 0) {
        return 0;
    }

    return number_format($number, $decimals, '', '');
}


echo "<pre>";
print_r($source_account_new);die;