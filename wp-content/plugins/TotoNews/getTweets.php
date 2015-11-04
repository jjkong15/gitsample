<?php

/*************************************************************************************************
 * this php file is to get keywords-language-location-related tweets using the Twitter Search API
 * ***********************************************************************************************/

header('Content-type: application/xml');

//get four variables: q (= keyword), lancode (=language code: ISO 639-1 alpha-2), latitude, longitude
$theKeyword=urldecode($_REQUEST['q']);
$lancode=urldecode($_REQUEST['lancode']);
$latitude=urldecode($_REQUEST['latitude']);
$longitude=urldecode($_REQUEST['longitude']);

//specify the location range for the tweets using latitude and longitude: within 10000 miles if both latitude and longitude are not empty
$locationSt="";
if(!empty($latitude) && !empty($longitude)){ $locationSt=$latitude.",".$longitude.",10000mi";}

$st='<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel><title>'.htmlspecialchars($theKeyword, ENT_XML1).'</title>';

if($theKeyword==""){$st .= '<item></item></channel></rss>'; echo $st; return;}  //return empty content if the keyword is empty

require_once 'Lib/twitteroauth-master/twitteroauth/twitteroauth.php';


function search(array $query)
{
    //construct TwitterOAuth object. The following keys are fake keys for the sample purpose, you may get them from 
    $consumer_key="1Vswx5l5bGS4jFyQVgi2BLRil";
    $consumer_secret="C3WMV0xyISP8tAPnHNu7lQFGlG37h4WJDtqZQbgl6alA6lgcGw";
    $oauth_token="384927756-m32fvWs3GIKNIZT8xzD6lK59k9rKfKAUN8VOkVuV";
    $oauth_token_secret="APdzLpm4aaSb58Q0aTfcs14z7922KY2Otr3Bejk1wFcBG";

    $toa = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
    return $toa->get('search/tweets', $query);
}

    //"lang" => $lancode,
    //"geocode" => $latitude.",".$longitude.",10000mi",

//http://techiella.com/twitter-search-using-the-twitter-api-php/
$query = array(
  "q" => $theKeyword,
  "count" => 80,
  "lang" => $lancode,
  "geocode" => $locationSt,
  'result_type' => 'mixed'      
);
$results = search($query);

if(count($results->statuses)<=3) {
    $query = array(
        "q" => $theKeyword,
        "count" => 80,
        "lang" => $lancode,
        'result_type' => 'mixed'      
    );
    $results = search($query);
}
if(count($results->statuses)==0) {
    $query = array(
        "q" => $theKeyword,
        "count" => 80,
        'result_type' => 'mixed'      
    );
    $results = search($query);
}

//htmlspecialchars($contentSt, ENT_XML1)
$contentSt="";
foreach ($results->statuses as $result) {
    $userName=$result->user->screen_name;
    $idSt=$result->id;
    $contentSt = '<div><span>@<a style="color:#0084B4;" href="http://twitter.com/';
    $contentSt .= $userName.'" rel="external" target="_blank">';
    $contentSt .= $userName.'</a>:&nbsp;</span>'; 
    //$contentSt .= '<span>' . $locationSt.$theKeyword.$lancode . '</span></div>';
    $contentSt .= '<span>' . $result->text . '</span></div>';
    $contentSt .= '<div style="font-size: 10px;text-align: center;color:#0084B4;">';
    $thelink='https://twitter.com/intent/tweet?in_reply_to='.$idSt;
    $theTitle='reply';
    $contentSt .= '<a href="' . $thelink . '" target="_BLANK">' . $theTitle . '</a>&nbsp;&nbsp;';
    //$contentSt .= '<a href="https://twitter.com/intent/tweet?in_reply_to='.$idSt.'" target="_BLANK">reply</a>&nbsp;&nbsp;';
    $contentSt .= '<a href="https://twitter.com/intent/retweet?tweet_id='.$idSt.'" target="_blank">retweet</a>&nbsp;&nbsp;';
    $contentSt .= '<a href="https://twitter.com/intent/favorite?tweet_id='.$idSt.'" target="_blank">favorite</a>';
    $contentSt .= '</div>';
    //$contentSt .=  "<span style='color:#0084B4;'>time: " . $result->created_at . ":&nbsp;</span><span class='author_name'>by " . $result->user->name . "</span><br />";
    
    $contentSt .=  "<br />";//time-ago
    $st .= '<item>';
    $st .=  '<description>'. htmlspecialchars($contentSt, ENT_XML1).'</description>';
    $st .= '</item>';
    
}
$st .= '</channel></rss>';
echo $st;
//foreach($string as $items)
//{
//    echo "Time and Date of Tweet: ".$items['created_at']."<br />";
//    echo "Tweet: ". $items['text']."<br />";
//    echo "Tweeted by: ". $items['user']['name']."<br />";
//    echo "Screen name: ". $items['user']['screen_name']."<br />";
//    echo "Followers: ". $items['user']['followers_count']."<br />";
//    echo "Friends: ". $items['user']['friends_count']."<br />";
//    echo "Listed: ". $items['user']['listed_count']."<br /><hr />";
//}