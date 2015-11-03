<?php
header('Content-type: application/xml');
//Build the URL in the proxy, this is a security thing
$theURL= 'https://news.google.com/news/feeds?q=' . urlencode($_REQUEST['keywords']) . '&output=rss';
//$theURL=str_replace("!!", "&", $theURL);
//$theURL='https://news.google.com/news/feeds?output=rss&q=soybeans';

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