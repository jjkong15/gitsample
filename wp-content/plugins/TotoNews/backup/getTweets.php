<?php
header('Content-type: application/xml');
$theKeyword=urldecode($_REQUEST['q']);

$st='<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel><title>'.htmlspecialchars($theKeyword, ENT_XML1).'</title>';
////*****
//$st1 =  "<span style='color:#0084B4;'>" . $result->user->screen_name . ":&nbsp;</span><span> " . $result->text . "</span><br />";
//$st1 .=  "<span style='color:#0084B4;'>time: " . $result->created_at . ":&nbsp;</span><span class='author_name'>by " . $result->user->name . "</span><br />";

//$st1 .=  "<br />";//time-ago

//$st .= '<item>';
//$st .=  '<link>'.htmlspecialchars('Jane', ENT_XML1).'</link>';
//$st .=  '<pubDate>'.htmlspecialchars('Jane Kong', ENT_XML1).'</pubDate>';
//$st .=  '<description>'.htmlspecialchars($st1, ENT_XML1).'</description>';
//$st .= '</item>';

//$st .= '</channel></rss>'; echo $st; return;

//*******
if($theKeyword==""){$st .= '<item></item></channel></rss>'; echo $st; return;}  //wp_die();
require_once 'Lib/twitteroauth-master/twitteroauth/twitteroauth.php';



function search(array $query)
{
    $toa = new TwitterOAuth('1Vswx5l5bGS4jFyQVgi2BLRil', 'C3WMV0xyISP8tAPnHNu7lQFGlG37h4WJDtqZQbgl6alA6lgcGw', '384927756-m32fvWs3GIKNIZT8xzD6lK59k9rKfKAUN8VOkVuV', 'APdzLpm4aaSb58Q0aTfcs14z7922KY2Otr3Bejk1wFcBG');
    return $toa->get('search/tweets', $query);
}


    //http://techiella.com/twitter-search-using-the-twitter-api-php/
    $query = array(
      "q" => $theKeyword,
      "count" => 50,
      'result_type' => 'popular'      
    );
    
    $results = search($query);
//htmlspecialchars($contentSt, ENT_XML1)
    $contentSt="";
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

?>