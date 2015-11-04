<?php

/*************************************************************************************************
 * this php file is to get keywords-language-location-related tweets using the Twitter Search API
 * ***********************************************************************************************/

header('Content-type: application/xml');

//get four variables: q (= keyword), lancode (=language code: ISO 639-1 alpha-2), latitude, longitude
$theKeyword=urldecode($_REQUEST['q']);

//return empty content if the keyword is empty (do this at very beginning to avoid unnecessary works if the keyword is empty )
if(empty($theKeyword)){
    echo '<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel><item></item></channel></rss>'; 
    return;
}  

$lancode=urldecode($_REQUEST['lancode']);
$latitude=urldecode($_REQUEST['latitude']);
$longitude=urldecode($_REQUEST['longitude']);

//specify the location range for the tweets using latitude and longitude: within 10000 miles if both latitude and longitude are not empty
$locationSt="";
if(!empty($latitude) && !empty($longitude)){ 
    $locationSt=$latitude.",".$longitude.",10000mi";
}

$st='<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel><title>'.htmlspecialchars($theKeyword, ENT_XML1).'</title>';


require_once 'Lib/twitteroauth-master/twitteroauth/twitteroauth.php';


function search(array $query)
{
    //construct TwitterOAuth object. The following keys are fake keys for the sample purpose, you may get them by registering an application at https://dev.twitter.com/apps
    //
    $consumer_key="1VswxxxxxxxxxxxxxxxxxxxxRil";
    $consumer_secret="C3WMV0xyISP8xxxxxxxxxxxxxxxxxlgcGw";
    $oauth_token="384927756-m32xxxxxxxxxxxxxxxxxxxxxxxxVuV";
    $oauth_token_secret="APdxxxxxxxxxxxxxxxxxxxxxxxxxxxwFcBG";

    $toa = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
    return $toa->get('search/tweets', $query);
}

$query = array(
  "q" => $theKeyword,
  "count" => 80,
  "lang" => $lancode,
  "geocode" => $locationSt,
  'result_type' => 'mixed'      
);
$results = search($query);

//if not many tweets (less than 3) in the results, look for global tweets (not specify geocode) 
if(count($results->statuses)<=3) { //if you only get less than 3 tweets, then 
    $query = array(
        "q" => $theKeyword,
        "count" => 80,
        "lang" => $lancode,
        'result_type' => 'mixed'      
    );
    $results = search($query);
}
//if still no tweets in the results, try to get the tweets without language restriction
if(count($results->statuses)==0) {
    $query = array(
        "q" => $theKeyword,
        "count" => 80,
        'result_type' => 'mixed'      
    );
    $results = search($query);
}

//if still no tweets in the results, return empty content
if(count($results->statuses)==0) {
    echo '<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel><item></item></channel></rss>'; 
    return;
}  

//display all tweets
foreach ($results->statuses as $result) {
    $userName=$result->user->screen_name;
    $idSt=$result->id;
    $contentSt = '<div><span>@<a style="color:#0084B4;" href="http://twitter.com/';
    $contentSt .= $userName.'" rel="external" target="_blank">';
    $contentSt .= $userName.'</a>:&nbsp;</span>'; 
    $contentSt .= '<span>' . $result->text . '</span></div>';
    $contentSt .= '<div style="font-size: 10px;text-align: center;color:#0084B4;">';
    $thelink='https://twitter.com/intent/tweet?in_reply_to='.$idSt;
    $theTitle='reply';
    $contentSt .= '<a href="' . $thelink . '" target="_BLANK">' . $theTitle . '</a>&nbsp;&nbsp;';
    $contentSt .= '<a href="https://twitter.com/intent/retweet?tweet_id='.$idSt.'" target="_blank">retweet</a>&nbsp;&nbsp;';
    $contentSt .= '<a href="https://twitter.com/intent/favorite?tweet_id='.$idSt.'" target="_blank">favorite</a>';
    $contentSt .= '</div>';
    $contentSt .=  "<br />";
    $st .= '<item>';
    $st .=  '<description>'. htmlspecialchars($contentSt, ENT_XML1).'</description>';
    $st .= '</item>';    
}
$st .= '</channel></rss>';
echo $st;