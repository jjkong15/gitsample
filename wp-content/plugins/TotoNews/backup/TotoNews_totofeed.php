<?php
//Here are valid subjects:
//
//Education
//Science/Technology
//Environment
//Health
//Legal
//International
//Business/Economy
//Art/Entertainment
//Politics
//Not Working
//VOA - Swahili
//RSS - Swahili
//VOA - English
//VOA - Simple English
//RSS - Other
//

function totonews_http_get($url) {
    $request = fopen($url, "r");
    $result = "";
    while (!feof($request)) {
        $result .= fread($request, 8192);
    }
    fclose($request);
    return $result;
}

/*
// * Use cURL to get the file contents.
// * (Using instead of file_get_contents() because some hosts disallow it)
// */
//function totonews_http_get ($Url) {
//    if (!function_exists('curl_init')){
//        die('CURL is not installed!');
//    }

//    $ch = curl_init();
//    curl_setopt( $ch, CURLOPT_URL, $Url );
//    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//    $output = curl_exec( $ch );
//    curl_close( $ch );
//    return $output;
//}
function totonews_query($search=NULL, $count=NULL, $category=NULL, $exclude=NULL)
{
    
    if(trim($search) == '' || !preg_match('/\w/', $search))
        $search = NULL;
    if($category=='Google')
        return totonews_query_google($search);
    
    if($category=='Yahoo')
        return totonews_query_yahoo($search);
    
    if($category=='Bing')
        return totonews_query_bing($search);
    
    //if($category=='World News')
    //    return totonews_query_world_news($search);
    
    //http://rss.wn.com/English/top-stories	World News
    //http://rss.wn.com/English/keyword/africa	World News - Africa
    //http://rss.wn.com/English/keyword/health	World News - Health
    //http://rss.wn.com/English/keyword/sport	World News - Sports
    //http://rss.wn.com/English/keyword/entertainment	World News - Entertainment
    //http://rss.wn.com/English/keyword/business	World News - Business
	

    $feed = "http://54.251.58.19/news/search?"; // "http://54.251.58.19/news/search?";(on weather server);   //"http://www.totoagriculture.org/news/search?" (on video10)
    if( ($search===NULL || $search==='') && ($count===NULL))
        $count = 50;
    if($search!==NULL && $search!='')
        $feed .= 'query=' . urlencode($search);
    if($count!==NULL)
        $feed .= '&count=' . intval($count);
    if($category!==NULL && $category!=='')
        $feed .= '&category=' .  urlencode($category);
    if($exclude!==NULL)
        $feed .= '&exclude=' . urlencode(implode(';', $exclude));
    return simplexml_load_file($feed);
}

function totonews_clean_description($description)
{
    return preg_replace('/^\s*\<\s+(.*)$/', '$1', preg_replace('/^\s*\[([^\]]+)\]\s*(.*)$/', '$1 - $2', $description));
}

function totonews_query_rss($url)
{
    $contents = simplexml_load_file($url);
    if($contents === NULL || $contents->channel === NULL)
        return NULL;
    return $contents->channel->xpath('item');    
}

function totonews_query_rss_string($url)
{
    $contents = simplexml_load_string(totonews_http_get($url));
    if($contents===NULL || $contents->channel===NULL)
        return NULL;
    return $contents->channel->xpath('item');    
}

function totonews_bing_query($keywords=NULL)
{
    if($keywords===NULL)
        return "http://www.bing.com/news/results.aspx?format=rss";
    return "http://www.bing.com/news/results.aspx?format=rss&q=" . urlencode($keywords); 
}

function totonews_query_google($keywords)
{
    if($keywords!==NULL && trim($keywords)!='')
        return totonews_query_rss("https://news.google.com/news/feeds?pz=1&cf=all&output=rss&q=" . urlencode($keywords));
    else
        return totonews_query_rss("https://news.google.com/news/feeds?pz=1&cf=all&output=rss");    
}

function totonews_query_yahoo($keywords)
{
    if($keywords!==NULL && trim($keywords)!='')
        return totonews_query_rss_string("http://news.search.yahoo.com/news/rss?toggle=1&ei=UTF-8&datesort=1&p=" . urlencode($keywords));
    else
        return totonews_query_rss_string("http://news.yahoo.com/rss/topstories");
    //return totonews_query_rss("http://news.search.yahoo.com/news/rss?toggle=1&ei=UTF-8&datesort=1");
}

function totonews_query_bing($keywords)
{
    if($keywords!==NULL && trim($keywords)!='')
        return totonews_query_rss_string(totonews_bing_query($keywords));
    else
        return totonews_query_rss_string(totonews_bing_query());
}

function totonews_query_world_news($keywords)
{
    if($keywords!==NULL && trim($keywords)!='')
        return totonews_query_rss_string("http://rss.wn.com/English/keyword/" . urlencode($keywords));   
    else
        return totonews_query_rss_string("http://rss.wn.com/English/keyword/world"); //"http://rss.wn.com/English/keyword/world");     
}
/*function totonews_parse_rss($content, $categoryName)
{
$output="";
$sourceSt="";
$desSt="";

if(!isset($content->channel))
{
return 'Could not query this feed - ' . $categoryName;
}

foreach($content->channel->xpath('item') as $article)
{
$sourceSt=trim($article->link);
$sst=substr($sourceSt, 0, 5);
if ($sst=="http:"){ $sourceSt=substr($sourceSt, 7); }
if(strpos($sourceSt, "/")>=0){$sourceSt=substr($sourceSt, 0, strpos($sourceSt, "/"));}

if($categoryName == 'Google')
$desSt = strip_tags(explode('<font size="-1">', $article->description)[2]);
else
$desSt=$article->description;


$desSt=totonews_clean_description($desSt);

if(mb_strlen($desSt)<16)
continue;
if(substr($desSt, -3)=="..."){
$desSt=substr($desSt, 0, strlen($desSt)-3);
}

$imageURL = NULL;
if($categoryName=='Google')
{
if(preg_match('~<img[^>]*src\s?=\s?[\'"]([^\'"]*)~i',$article->description, $imageURL)>0)
$imageURL = $imageURL[1];
else
$imageURL = NULL;
preg_match('@src="([^"]+)"@', $article->description, $match);
//$article->description = shortdesc($article->description, $atts['length']);
}



if( isset($article->enclosure) )
{
$imageURL = $article->enclosure->attributes()->url;
//Format looks like this in XML
//<enclosure type="image/jpeg" url="http://cdn.wn.com/ph/img/51/f4/4c962c444ac8783a8ca2cc698ee9-grande.jpg"/>
}


$output .= '<div style="overflow: hidden">';
$output .= '<p style="margin: 0 !important">';
$output .= '<a target="_blank" rel="nofollow" href="'.$article->link.'" style="color:red">' .$article->title. '</a>';
$output .= '<br />';
$output .= '<span>' .$sourceSt. ' | Published on ' .str_replace("12:00:00 AM", "", $article->pubDate).'</span></p>';
//$output .= '<br style="clear: both;" />';
if($imageURL !== NULL)
$output .= '<img src="' . $imageURL . '" alt="" style="float: left; margin: 5px 5px 5px 5px; max-width: 80px; max-height: 80px;" />';
$output .= '<p style="margin: 0 !important">' . $desSt. '<a target="_blank" href="'.$article->link.'" style="color:blue"> ...</a></p>';
$output .= '</div><br style="clear: both;" />';
}
if($output!=''){ $output='<br />'.$output;} //'</div><br style="clear: both;padding-bottom:10px;" />';}
return $output;
}*/

?>