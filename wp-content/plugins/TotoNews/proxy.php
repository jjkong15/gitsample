<?php
header('Content-type: application/xml');
//Build the URL in the proxy, this is a security thing
$theCountry=$_REQUEST['geo'];

$theURL= 'https://news.google.com/news/feeds?q=' . $_REQUEST['keywords'] . '&output=rss';
if(!empty($theCountry)){ $theURL.="&geo=".$theCountry;}

//$theURL=str_replace("!!", "&", $theURL);

$handle = fopen($theURL, "r");

if ($handle) {
    //echo '<span>url: '.$theURL.'</span>';
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        echo $buffer;
    }
    //echo '<span>url: '.$theURL.'</span>';
    fclose($handle);
}
?>