<?php
/*
Plugin Name: TotoNews
Plugin URI: http://www.totogeo.org/plugins/news
Description: Plugin for displaying news
Author: Jane Kong
Version: 1.0
Author URI: http://www.totogeo.org/plugins/news
 */
require_once('TotoNews_totofeed.php');
//*************** Admin function ***************
function totonews() {
    include_once('TotoNews_admin.php');
}

include_once('TotoNews_widget.php');

add_action('widgets_init', 'TotoNews_widgets_init');

function TotoNews_widgets_init()
{
    
}


add_shortcode('TotoNews', 'TotoNews_shortcode');
add_shortcode('TotoNewsWidget', 'totonews_small_toto_new');

/**
 * Adds the WordPress Ajax Library to the frontend.
 */


function totoNews_add_ajax_library() {
}

function getButtonsContent($defaultCat, $catsArray,$displaySt,$contentIndex,$imgPathArr, $topCatName,$baseID){
    $output = '';

    $output .= '<div id="tnscontent'.$contentIndex.'" style="display:'.$displaySt.';width:100%;margin-left: 5px;";>';
    $numOfBtns=count($catsArray);

    for($x=0;$x<$numOfBtns;$x++)
    {
        $theKeywords="";
        if($topCatName!='' && $topCatName!='World' && $topCatName!='International' && $topCatName!='Headlines') {$theKeywords=$topCatName;}
        if($catsArray[$x]!='' && $catsArray[$x]!='World' && $catsArray[$x]!='International' && $catsArray[$x]!='All' && $catsArray[$x]!='Headlines') {
            if($theKeywords==''){
                $theKeywords=$catsArray[$x];
            }else{
                $theKeywords=$theKeywords.', '.$catsArray[$x];
            }
        }
        $output .='<button onclick="level2BtnClicked3(\''.$topCatName.'\', \'' . __($catsArray[$x]) .'\', \'' .$contentIndex.'\', \'' .($x+1) .'\', \'' . $theKeywords . '\');" id="'.$baseID.($x+1).'" type="button" style="white-space: nowrap;"><img style="margin-top:3px" src="' .plugins_url('icons/' . $imgPathArr[$x], __FILE__) . '" /><br />' . __($catsArray[$x], 'News') . '</button>';
    }
    $output .= '</div>';
    return $output;
}

function totonews_getTOCs($num){
    
    $output .= '<div class="news-1 newshiddenlink"><a style="color:#4747FF;" href="#news-'.$num.'">';
    $output .= '<span id="totonews_toc'.$num.'"></span></a></div>';
    return $output;
}
function totonews_prepareForTrans($st){
    
    $st=str_replace("&", " & ", $st);
    $st=str_replace("-", " - ", $st);
    $st=str_replace("(", " ( ", $st);
    $st=str_replace(")", " ) ", $st);
    $st=str_replace("/", " / ", $st);
    $st=str_replace("\\", " \\ ", $st);
    $st=str_replace(";", " ; ", $st);
    $st=str_replace(",", " , ", $st);
    $st=str_replace(":", " : ", $st);
    $st=str_replace(".", " . ", $st);
    $st=str_replace("!", " ! ", $st);
    //process
    $theSt="";
    if(stripos($st, " ")===false){return $st;}
    
    $arr2=explode(" ", $st);
    foreach($arr2 as $tempSt2){
        if(!empty($tempSt2)){
            if($tempSt2=="&"){ //both need space
                $theSt = trim($theSt)."} " .$tempSt2. " {";
            }elseif($tempSt2=="-" || $tempSt2=="/" || $tempSt2=="\\"){  //no space needed
                $theSt = trim($theSt)."}". $tempSt2."{";
            }elseif($tempSt2=="("){ //need space before
                $theSt = trim($theSt)."} ". $tempSt2."{";
            }
            elseif($tempSt2==")" || $tempSt2=="," || $tempSt2==";"|| $tempSt2=="." || $tempSt2==":" || $tempSt2=="!"){ //need space after
                $theSt = trim($theSt)."}". $tempSt2." {";
            }else{
                if(totonews_endsWith(trim($theSt), "{")){
                    $theSt = trim($theSt).$tempSt2."";
                }else{
                    $theSt = trim($theSt)." ".$tempSt2."";
                }
            }
        }
        $theSt=trim($theSt);
    }
    $theSt="{".trim($theSt)."}";
    $theSt=trim(str_replace("{}", "", $theSt));
    $theSt=preg_replace("/(\))\s+(\;|\.|\:|\,|\)|\?|\!)/", "$1$2", $theSt);
    
    return $theSt;   
}

function totonews_endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}
function totonews_startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}

function totonews_format_keywordsForTweets($keywords, $lanToTranslate, $useTranFunction)
{
    
    $output = ""; //'<words>' OR '<words>'

    
    
    if (!empty($keywords))
    {
        $keywords_array = preg_split('/ *\; */', $keywords);
        //$keywords_array = array_slice($keywords_array);
        //echo "before:" .$keywords.'<br />';
        foreach($keywords_array as $specific_string){
            if(trim($specific_string)!='')
            {
                if($useTranFunction){
                    $specific_string=__($specific_string, 'News'); 
                }elseif(!empty($lanToTranslate)){
                    $specific_string=tototranslation_translate_to_language($specific_string,  "News", $lanToTranslate);
                }
                $output .= " OR '".$specific_string."'";
                
            }
        }
        $output=trim($output);
        if(!empty($output)){$output=substr($output, 3);}
    }
    return $output;
}
function createOneSpanTotoNews($headText, $buttonText, $rowNum, $colNum, $keywordsStFromButtons, $keywordsStFromButtonsForGoogle, $theLan, $categoryForThisButton="", $subRowNumSt, $useFloatDiv, $useSeanWayToTest, $idNum, $language, $theISO2, $textInRowLan, $lancode, $latitude,  $longitude, $keywordsForTweets){  
    //check if the button already created
    //global $arrOfButtonTexts;
    //$theStToCheck=__($buttonText);  //__($headText).";".__($buttonText);//////
    //if(in_arrayi($theStToCheck, $arrOfButtonTexts) && $buttonText!="All"){return "";} //no need to check duplications
    //array_push($arrOfButtonTexts, $theStToCheck);
    $keywords=$keywordsStFromButtons;
    $keywordsForGoogle=$keywordsStFromButtonsForGoogle;
    $retval='';
    $categoryName=$buttonText;
    if($categoryForThisButton!="")
    {
        $keywords = "";  //golden, no keywords need
        $keywordsForGoogle="";
        $categoryName=$categoryForThisButton;
    }
    else 
    {
        $categoryName = "";  //not golden
    }
    
    global $translation_language;
    $transLan= $translation_language;
    
    //$retval .= '<span style="cursor: pointer;" onmouseover="style.textDecoration = \'underline\'" onmouseout="style.textDecoration = \'none\'"';
	$retval .= '<a ';	
    $idSt='totonewsButton_' . $idNum;
    $titleToShow=$buttonText;
    if($buttonText=="All"){
        $titleToShow=$headText;
    }
    if($titleToShow=="{Swine} / {Pigs}"){$titleToShow="Swine";}
    
    $retval .= ' headtextOnRow="' . htmlentities($headText) . '" ';
    
    //if($language!="eng-us" && !empty($language)) {$retval .= ' title="' . htmlentities($titleToShow) . '" ';}
    $retval .= ' title="' . htmlentities($titleToShow) . '" ';
    $retval .= ' titletoshow="' . htmlentities($titleToShow) . '" ';
    $retval .= ' textInRowLan="' . htmlentities($textInRowLan) . '" ';
    $retval .= ' translan="' . htmlentities($transLan) . '" ';
    
    //$lancode, $latitude,  $longitude, $keywordsForTweets
    if(empty($lancode)){
        $retval .= ' lancode=""';
    }else{
        $retval .= ' lancode="' . htmlentities($lancode) . '" ';
    }
    if(empty($latitude)){
        $retval .= ' latitude=""';
    }else{
        $retval .= ' latitude="' . htmlentities($latitude) . '" ';
    }
    if(empty($longitude)){
        $retval .= ' longitude=""';
    }else{
        $retval .= ' longitude="' . htmlentities($longitude) . '" ';
    }
    
    
    if(empty($language)){
        $retval .= ' language=""';
    }else{
        $retval .= ' language="' . htmlentities($language) . '" ';
    }
    if(empty($theISO2)){
        $retval .= ' iso2=""'; 
    }else{
        $retval .= ' iso2="' . htmlentities($theISO2) . '" ';
    }
    $retval .= ' categoryname="' . htmlentities($categoryName) . '" ';
    $retval .= ' id="' . $idSt . '" ';
    
    //for translated today string and yesterday string and $keywordsForTweets
    $todaySt="";
    $yesterdaySt="";
    
    if($headText=="English"){ //do not translate buttons if they are on "English" row
        $todaySt="Today";
        $yesterdaySt="Yesterday";
        $keywordsForTweets=totonews_format_keywordsForTweets($keywordsForTweets, "", false);
    }elseif(!empty($language) && $language!="eng-us"){
        $todaySt=tototranslation_translate_to_language("Today", "News", $language);
        $yesterdaySt=tototranslation_translate_to_language("Yesterday", "News", $language); 
        $keywordsForTweets=totonews_format_keywordsForTweets($keywordsForTweets, $language, false);
        //echo " lan=" .$language. " today=".$todaySt." yesterday=".$yesterdaySt;
    }else{
        $todaySt=__("Today", News);
        $yesterdaySt=__("Yesterday", News); 
        $keywordsForTweets=totonews_format_keywordsForTweets($keywordsForTweets, "", true);       
    }
    $retval .= ' today="' . htmlentities($todaySt) . '" ';
    $retval .= ' yesterday="' . htmlentities($yesterdaySt) . '" ';
    $retval .= ' keywordsForTweets="' . htmlentities($keywordsForTweets) . '" ';
    
    
    
    $retval .= 'class="totonews-google totonews-button">';  // totonews-button
    
    $buttonText=str_replace(" and ", " & ", $buttonText);
    $buttonText=totopeople_upper_first($buttonText);
    
    if($headText=="English"){ //do not translate buttons if they are on "English" row
        $retval .= $buttonText;
    }elseif(!empty($language) && $language!="eng-us"){
        //echo " :".$buttonText ." = ".tototranslation_translate_to_language($buttonText,  "News", $language); 
        //if($language=="spa-es" and $buttonText=="Headlines") {echo tototranslation_translate_to_language("Headlines",  "News", "spa-es");} 
        $retval .= tototranslation_translate_to_language($buttonText,  "News", $language);
    }else{
        
        $retval .= __(totonews_prepareForTrans($buttonText));
    }
    //if($language=="spa-es") {echo " 1:".tototranslation_translate_to_language("Headline",  "News", $language);}  //Titular
    //if($language=="fra-fr") {echo " 2:".tototranslation_translate_to_language("Headlines",  "News", $language);} //Manchettes
    $retval .= '</a>';
    return $retval;
}


function totonews_getSearchBox($selectedISO2){
    $st='';
	if ($selectedISO2=="") { 
		$selectedISO2 = $_GET["iso2"];
	}
    $st.='<span><h2 style="display: inline-table;vertical-align: sub;margin-right:5px;">' . htmlentities(__("News", "News")) . ':</h2> ';
    $st.='<select style="height:35px;margin-top:-1px;" id="totoNewsCountryChoice" onChange="totonews_newDoc(this.value);">';
    //list all countries//////
    global $wpdb;
    $configs=$wpdb->get_results("select name, iso2 from totolocation_countries order by name");
    foreach($configs as $config){
        $curIso2=strtoupper($config->iso2);
        if($curIso2==$selectedISO2){            
            $st.='<option value="'.$config->iso2.'" selected="selected">'.htmlentities(__($config->name, "News")).'</option>';
        }else{
            $st.='<option value="'.$config->iso2.'">'.htmlentities(__($config->name, "News")).'</option>';
        }
    }
    //done with all countries
    $st.='</select>';
    $searchTranSt=esc_attr(__("Search", "News"));
    $st.='<input type="text" id="totoNewsSearch" name="totonews_keywordsSearch" onkeypress="searchKeyPress(event);" style="margin-left:5px;margin-right:5px;width:40%"/>';
    $st.='<button id="totoNewsSearchButton" onclick="searchboxClicked();">'.__("Search").'</button>';
    
    $st.='</span>';
    return $st;    
}

function totonews_getNewsContentSt($keywordsStFromButtons, $keywordsStFromSearchBox, $lowestSt, $iso2, $theLanguage){
    global $arrOfButtonTexts;
    global $wpdb;
    global $translation_language;
    $theLanguage = $translation_language;
   
    $arrOfButtonTexts=array();
    //***************** end get keywords **************
    $tSt = "";
    $categoryForWorldNews="World News";
    $last1=""; $last2=""; $last3="";
    if(!empty($keywordsStFromSearchBox)){
        $tSt=__("Search").": " .$keywordsStFromSearchBox;
    }else if(!empty($keywordsStFromButtons)){
        list($last1, $last2, $last3) = explode("|", $keywordsStFromButtons); 
        if($last2=="" && $last3==""){
            $tSt = __($last1, 'News');
            $categoryForWorldNews=$last1;
        }elseif($last3==""){
            $tSt = __($last2, 'News').' - ' . __($last1, 'News');
            $categoryForWorldNews=$last2;
        }else{
            $tSt = __($last3, 'News').' - ' . __($last2, 'News').' - ' . __($last1, 'News');
            $categoryForWorldNews=$last3;
        }
    }
    
    $output = '';
	$output .= "<div class='page-title'>";

    
    // We need to calculate the ISO2 first
    $location = "";
    $theCountry="";  //"Ethiopia"; //////
    $theCity="";  //"Ethiopia"; //
    $theISO2="";
    $latitude="";
    $longitude="";
    
    if (function_exists("totolocation_get_dashboard_location") ){
        $location = totolocation_get_dashboard_location();
        $theCountry=$location->country;  //"Ethiopia"; //
        $theCity=$location->city;  
        $theISO2=$location->ISO2;  
        $latitude=$location->latitude;
        $longitude=$location->longitude;
    }
    
    if (!empty($_REQUEST['iso2'])) 
    {
        if($theISO2!=$_REQUEST['iso2']){            
            $theISO2=strtoupper($_REQUEST['iso2']);           
            $theCountry=$wpdb->get_var($wpdb->prepare("select name from totolocation_countries where iso2=%s", $theISO2));
        }
    }

    if(!empty($iso2)){ //if Crop page passed $iso2, it overwrites other iso2
        $theISO2=$iso2;
    }
    
    //$output .= "<div style='border-bottom: 2px solid #694E90; margin-top: -15px;'>";
    $output .= totonews_getSearchBox($theISO2);
	$output .= "</div>";
    //the language    
    
 
    
    if(!empty($keywordsStFromSearchBox))
    {
        $keywordsStFromSearchBox=totonews_format_keywordsWithSemiColon($keywordsStFromSearchBox);
        $keywordsStFromButtons="";
    }
    
    //start to form menu buttons from mySql table
    $stForTelecom="";
    $type = get_option('dashboard_type', 'radio');
    //echo "type:" .$type;
    if($type=="hyperstrat" || $type=="telecom" || $type=="Executive"){  //"Executive"="hyperstract"
        $stForTelecom=" or headTextOnRow='Telecom' or (headTextOnRow='English' and headImg='Globe (2)')";  //show "Telecom" row and "English" with global image row
    }else{
        $stForTelecom=" and headTextOnRow<>'Telecom' and not (headTextOnRow='English' and headImg='Globe (2)')";  //do not show "Telecom" row and "English" with global image row
    }
    $sqlSt=$wpdb->prepare('select iso2 from dashboard.totonews_rowsinfo where iso2=%s and notValid=false', $theISO2);
    
    $thev=$wpdb->get_results($sqlSt);
    if(empty($thev)){  //use old version      
        $sqlSt=$wpdb->prepare('select * from dashboard.totonews_rowsinfo where notValid=false and (iso2 is null '.$stForTelecom.') order by rowNum, colNum', $theCountry);
    }else{  //new version
        $sqlSt=$wpdb->prepare('select * from dashboard.totonews_rowsinfo where notValid=false and (iso2=%s '.$stForTelecom.') order by rowNum, colNum', $theISO2);
    }
    
    //echo " sqlSt:".$sqlSt;
    $configs=$wpdb->get_results($sqlSt);
    
    $keywordsSt="";
    $keywordsStSwahili="";
    $oldRowNum=-1;
    $oldColNum=-1;
    //$output='';//
    $colNum=1;
    $useSeanWayToTest=true;
    $useFloatDiv=true;
    $subRowNum=1;
    $subRowNumSt="";
    
    $startPreferenceRow=false;
    $forPreference=false;
    $idNum=0;
    $countOnRow=0;
    
    $variables = array();
    //1. display preference rows//
    if(!key_exists('Favorites', $variables)) //'.__("favorites").'
        $variables['Favorites'] = array();
    
    foreach( getPreferenceButtons($location) as $pref)
    {
        $v = new stdClass();
        $v->buttonText = $pref;
        $v->headImg = "Globe (2)";
        $v->headTextOnRow = __("Favorites");
        $v->category="";
        $v->lanIso="";
        $v->iso2="";
        $variables['Favorites'][] = $v;
    }
    
	//2. display rows from totonews_rowsinfo
	if (!empty($configs))
	{
        //for buttons
        //for non-language first, global English second, English local third, languages fourth, then Telecom, then chosen language
        foreach ($configs as $config)  
        {
            
            $indexSt=$config->headTextOnRow;
            if(strcasecmp($config->headTextOnRow, 'English')==0 && $config->headImg=="Globe (2)"){
                $indexSt=$config->headTextOnRow."Globe";
            }
            if(!key_exists($indexSt, $variables)){
                $variables[$indexSt] = array();
            }
            
            
            $variables[$indexSt][] = $config;
            if($config->headImg!==null)
                $variables[$indexSt][0]->headImg = $config->headImg;
            
            if($config->headTextOnRow != "English" && empty($config->lanIso)){  //if it's not a language row, need to translate the headTextOnRow
                $variables[$indexSt][0]->headTextOnRow = __($config->headTextOnRow);
            }else{
                $variables[$indexSt][0]->headTextOnRow = $config->headTextOnRow;
            }
        }
    }
    //3. add chosen language as the last row if the choose language is not English, and such language doesn't exist for this country
    global $translation_language;  //lan iso code
    $choseTranslanIso=$translation_language;
    
    if($choseTranslanIso!="eng-us"){ 
        //check if selected language already exists for the selected country
        $choseTranslanName=$wpdb->get_var($wpdb->prepare("select distinct language FROM totonews_rowsinfo where lanIso=%s and iso2=%s", $choseTranslanIso, $theISO2));
        
        //changed wrong button: feedback: update dashboard.totonews_rowsinfo set buttonText="Economy" where buttonText="Economic"
        //update dashboard.totonews_rowsinfo set category="Economy" where category="Economic" -- didn't change rss_categories, it has ?? problem
        
        if(empty($choseTranslanName)){    
            $choseTranslanName=$wpdb->get_var($wpdb->prepare("select distinct language FROM totonews_rowsinfo where lanIso=%s", $choseTranslanIso));
            
            $extraTransLans=$wpdb->get_results($wpdb->prepare("select distinct buttonText, category, language, lanIso FROM totonews_rowsinfo where lanIso=%s order by buttonText", $choseTranslanIso));
            if(!empty($extraTransLans)){
                
                $variables[$choseTranslanName] = array();
                foreach ($extraTransLans as $extraTransLan)  //for other languages
                {
                    $variables[$choseTranslanName][] = $extraTransLan;
                }
                $variables[$choseTranslanName][0]->headImg = "Globe (2)";
                $variables[$choseTranslanName][0]->headTextOnRow = __($choseTranslanName);
                //echo "lan:".$variables[$choseTranslanName][0]->language;
            }
        }
    }	
    
    if(!empty($variables)){
        $output .= '<div style="padding: 2px 0;">';
    }

    $xcount = 1;
    foreach($variables as $configs)
    {
        if(count($configs)>0)
        {
            $output .= '<div style="padding-top: 5px;">'; // style="padding: 5px": if you want padding, add another div for padding, otherwise there can be overflow, don't just put it in the parent div
            $output .= '<span><b>';
            if($configs[0]->headTextOnRow=="English"){
                $output .= htmlentities($configs[0]->headTextOnRow);
            }else{
                $output .= htmlentities($configs[0]->headTextOnRow);
            }
            $output .= ': </b></span>';  //end of heading
            //start buttons
            
            $lanInUrl="";
            $icount = 1;
            foreach($configs as $config)
            {
                if (($icount==10) && (count($configs)>11)) { //Do only if item has more than 11
                    $output .= '<div style="display: none" id="totonews_moredata_' . $xcount . '">';
                }
                $textInRowLan="";
                $lanInUrl=$translation_language;
                $textInRowLan=$config->buttonText;
                if($textInRowLan=="Headlines"){
                    $textInRowLan=$theCountry;
                }
                if(!empty($config->lanIso)){
                    $textInRowLan=tototranslation_translate_to_language($textInRowLan,  "", $config->lanIso);
                    $lanInUrl=$config->lanIso;
                }
                //echo "(--------):" .$config->{lancodeForTwitter}."; ". $config->Longitude ."; ".  $config->Latitude."; ".  $config->keywords;
                
                $output .= createOneSpanTotoNews($config->headTextOnRow, $config->buttonText, $config->rowNum, $colNum,
                $theKeywords, $theKeywordsForGoogle, "", $config->category, $subRowNumSt, $useFloatDiv, $useSeanWayToTest, $idNum, $lanInUrl, $config->iso2, $textInRowLan, $config->lancodeForTwitter, $config->Latitude,  $config->Longitude, $config->keywords); //for now, language=""
                $idNum=$idNum+1;
                $output .= ", ";
                $icount++;
            }
            //$output = rtrim($output, ", ");
            if (count($configs)>11) { //Do only if item has more than 11
                $output .= '</div><a id="totonews_show_moredata_' . $xcount . '">' . __("more") . ' ...</a><a style="display: none;" id="totonews_show_lessdata_' . $xcount . '">' . __("less") . ' ...</a>';
            } else {
                $output = rtrim($output, ", ");
            }
            $output.='</div>'; //end of 5px padding container////////////////
        }
        $xcount++;
    }
    $output .= '</div><br/>';
    //~~~
	
	
	
	
	$output .= '<div>'; //66%
    //   $output .= totonews_getSearchBox($theISO2);////////
    $output .='<a name="ttnewscontent"></a>';
    $output .= '<span id="totonews_searchLoadingImg" style="display:none;"><img src="' . plugins_url('TotoNews/icons/ajax-loader.gif') . '" /></span>';
    $categoryForWorldNews="World News";
    //for World News Category
    if($categoryForRSSBengali=="Sports"){
        $categoryForRSSBengali="RSS - Bengali - Sports";
    }elseif($categoryForRSSBengali=="Entertainment"){
        $categoryForRSSBengali="RSS - Bengali - Entertainment";
    }else{
        $categoryForRSSBengali="RSS - Bengali";
    }
    $loading_image = '<br /><img src="' . plugins_url('TotoNews/icons/ajax-loader.gif') . '" />';
    $output .= '<div id="totonews_showLoadingImg" style="display:none">' .$loading_image. '</div>';
    
    $output .= '<div><div class="news-success-container"></div><div>';
    
    if(!empty($keywordsStFromSearchBox)){ $keywordsStFromButtons="";}
    
    $output .= totonews_get_news_from_one_category_place_holder(1, $keywordsStFromButtons, $keywordsStFromSearchBox, $lowestSt);
    
    $output .= '</div></div>';
    
    //**************** hidden number for orders *******************************
  
    $output .= '<span id="totonews_hiddenTodayTrans" style="display:none">'.__("Today").'</span>';
    //**************** hidden number for whichOneShows *******************************
    $output .= '<span id="totonews_hiddenYesterdayTrans" style="display:none">'.__("Yesterday").'</span>';
    //****** for latitude and longitude for twitter search from the search box
    $latitude=""; $longitude="";
                  $configs2 = $wpdb->get_results($wpdb->prepare("select distinct Latitude, Longitude from dashboard.totolocation_countries where iso2=%s", $theISO2));    
                  
    if (isset($configs2))
    {
        foreach ($configs2 as $config)  //for each button
        {
            $latitude=$config->Latitude; 
            $longitude=$config->Longitude; 
            break;
        }
    }
    //echo '$latitude:'.$latitude;
    //echo '$longitude:'.$longitude;
    //totonews_hiddenLatitude, totonews_hiddenLongitude
    $output .= '<span id="totonews_hiddenLatitude" style="display:none">'.$latitude.'</span>';
    $output .= '<span id="totonews_hiddenLongitude" style="display:none">'.$longitude.'</span>';
    //**************** hidden string for use Sean way or my way *******************************
    $sufStForSelectionStyle="";
    if($useSeanWayToTest){ $sufStForSelectionStyle="floatDiv";}
    //$output .= '<span id="totonews_hiddenUseWhichWay" style="display:none">'.$sufStForSelectionStyle.'</span>';
    if(empty($keywordsStFromSearchBox)){ return $output; }  //no need to call  ajax
    
    
    
    $output .= '</div>'; //<div class="totonews-sidebar-container" style="width: 34%; float: left;">';
    //$output .= '<br style="clear: both;" />';
    $output .= '</div> ';
    //$search="cow";$iso2="UG";
    if(!empty($keywordsStFromSearchBox) && !empty($theISO2)){ 
        //totonews_update_all_news2(titleToShowInEnglish, totonewsHolderDiv, buttonText, kwords, kwordsForGoogle, category, theLan, kwords2, googleURL, iso2, language, textInRowLan, headtextOnRow, todaySt, yesterdaySt, lancode, latitude, longitude, keywordsForTweets, urlPlanNum)
        $output.='<script>totonews_update_all_news2("'.$search.'", "totonews_holder1", "", "'.$search.'", "'.$search.'", "", "English", "", false, "'.$theISO2.'", "' . $theLanguage . '", "", "", "'.__("Today").'", "'.__("Yesterday").'", "", "'.$latitude.'", "'.$longitude.'", "", 1);</script>';   
    }
    return $output;
    
    //////////////**************** call Ajax ****************************
    ////////////$output .= '<script> totonews_update_all_news(); </script>';
    //////////////**************** Done *******************************
    ////////////return $output."<span>keywordsStFromSearchBox:".$keywordsStFromSearchBox ."</spn>";
}

function totonews_get_news_from_one_category_place_holder($num, $keywordsStFromButtons, $keywordsStFromSearchBox, $categoryName, $title=NULL, $tag=NULL)
{
    //if ($categoryName != "Rice" && $categoryName != "Cattle (beef)" && $categoryName != "Cattle (dairy)" && $categoryName != "Maize" && $categoryName != "Soybeans") {
    
    if(strcasecmp($categoryName, "Rice")==0 || strcasecmp($categoryName, "{Cattle} ({beef})")==0 ||strcasecmp($categoryName, "{Cattle} ({dairy})")==0 ||strcasecmp($categoryName, "Maize")==0 ||strcasecmp($categoryName, "Soybeans")==0){
        $keywordsStFromButtons = "";  //golden, no keywords need
        $keywordsStFromSearchBox="";
    }else {
        $categoryName = "";  //not golden
    }
    
    if(!empty($keywordsStFromSearchBox)){
        $keywordsStFromSearchBox=totonews_format_keywordsWithSemiColon($keywordsStFromSearchBox);
        $keywordsStFromButtons="";
    }
    if(!empty($keywordsStFromButtons)){            
        $keywordsStFromButtons = ltrim(totonews_query_keywords(strtoupper(implode('|', array_reverse(explode('|', $keywordsStFromButtons)))),  5, $categoryName));;
    }
    
    
    $tag = 'news-' . $num;
    $output = '<div id="totonews_titleHolder"></div>';
    
    $output .= '<div style="width:100%;float:left">'; //<h1>$keywordsStFromButtons:'.$keywordsStFromButtons.'</h1>'; // class="' . $tag . '">';
    if($title!==NULL && $tag!==NULL)
    {
        $output .= '<a name="news-' . $num . '">';
        $output .= '<br><span style="white-space:nowrap;"><span id="totonews_title'.$num.'" style="margin-left: 0px;margin-top: 20px;white-space:nowrap;color:black;font-weight:bold;font-size:25px;">';
        $output .= '</span>';
        $output .= '<a id="gotoID'.$num.'" style="display:none; margin-left:30px;color:#4747FF;" href="#top" title="' . __('Go to top', 'News') . '" target="_self">' . __('Go to top', 'News') . '</a></span>';  //$title .
    }
    $loading_image = '<br /><img src="' . plugins_url('TotoNews/icons/ajax-loader.gif') . '" />';
    
    
    //$output .= '<h1>cateName:'.$categoryName.' k1:'.$keywordsStFromButtons.' k2:'.$keywordsStFromSearchBox.'</h1>';
    //urlencode
    $output .= '<div class="totonews-container">';
    $output .= '<div class="totonews-left" style="width: 66%" id="totonews_holder'.$num.'" tag="' . $tag . '" title="' . htmlentities($title) . '" keywords1="' . base64_encode($keywordsStFromButtons). '" keywords2="' . base64_encode($keywordsStFromSearchBox) . '" category="' . base64_encode($categoryName) . '"></div>'; //$loading_image
    //$output .= '<div id="totonews_holder'.$num.'" tag="' . $tag . '" title="' . htmlentities($title) . '" keywords1="' . urlencode($keywordsStFromButtons). '" keywords2="' . urlencode($keywordsStFromSearchBox) . '" category="' . urlencode($categoryName) . '"></div>'; //$loading_image
    
    $output .= '<div class="totonews-right" style="width: 33%" id="tweetsDiv"></div>';
    $output .= '</div>';
    $output .= '</div>';
    return $output;
}
function totonews_get_and_operator($category)
{
    return ($category==='Google' ? ' AND ' : '+');
}

function totonews_get_or_operator($category)
{
    return ($category==='Google' ? ' OR ' : ' ');
}

function totonews_small_toto_new()
{
    $location = totolocation_get_dashboard_location();    
    $translation_city = __($location->city, 'Cities');
    
    global $translation_language;
    $transLan= $translation_language;
    
    
    $retval = ' titletoshow="' . htmlentities($translation_city) . '" ';
    $retval .= ' translan="' . htmlentities($transLan) . '" ';
    $output = '<script type="text/javascript"> totonews_update_all_newsFor2() </script>';
    
    return '<div style="font-size: 85%;" id="totonews_smallgooglenews" class="totonews-googlesmall" '. $retval. '></div>'.$output;  
    
    //return '<div style="font-size: 85%;">' . totonews_get_news_from_one_category('', '', 'Google', 'Google', false) . '</div>';        
}

//function totonews_get_news_from_one_category($keywordsStFromButtons, $keywordsStFromSearchBox, $categoryName, $title, $show_keywords=true)
//{

//    try
//    {

//        $output = '<div>';
//        $keywords=$keywordsStFromButtons;
//        if(!empty($keywordsStFromSearchBox)){
//            $keywords=$keywordsStFromSearchBox;
//        }

//        if($categoryName=="Google"){ goto origMethod;}

//        $articles_array[] = array(
//            'count' => 50,
//            'title' => "",
//            'category' => $categoryName,
//            'exclude' => NULL,
//            'keywords' => $keywords);

//        goto skipMain;

//        origMethod:
//        $intTitle=__('International', 'News');
//        $numOfNews=5; //for international news
//        if(!empty($keywordsStFromSearchBox)){$intTitle="";$numOfNews=20;}

//        $sourceSt="";
//        $desSt="";
//        $articles=NULL;

//        $location = totolocation_get_dashboard_location();
//        $theCountry=$location->country;  //"Ethiopia"; //
//        if(stripos($theCountry, "Tanzania")!==false){
//            $theCountry="Tanzania";
//        }

//        $translation_city = __($location->city, 'Cities');
//        $translation_country = __($theCountry, 'Countries');
//        $translation_continent = __($location->continent, 'Continents');
//        $articles_array = array();        


//        if($categoryName==="RSS - Other")
//        {
//            $exclude = array('VOA - Simple English','VOA - English','RSS - Swahili','VOA - Swahili','RSS - Amharic','VOA - Amharic','RSS - Bengali','VOA - Bengali','World News', 'World News - Africa', 'World News - Health', 'World News - Sports', 'World News - Entertainment', 'World News - Business', 'RSS - Bengali - Nation', 'RSS - Bengali - Sports', 'RSS - Bengali - Entertainment');
//            if(!empty($keywordsStFromSearchBox)){ goto skipToInternational1;}
//            //Local
//            $articles_array[] = array(
//                'count' => 30,
//                'category' => $categoryName,
//                'exclude' => $exclude,
//                'title' => $translation_city,
//                'keywords' => $keywords . totonews_get_and_operator($categoryName) . '("' . $location->city . '"' . ( $translation_city!=$location->city ? (totonews_get_and_operator($categoryName) . '"' . $translation_city . '"') : '') . ')');

//            //Regional
//            $articles_array[] = array(
//                'count' => 20,
//                'title' => $translation_country,
//                'category' => $categoryName,
//                'exclude' => $exclude,
//                'keywords' => $keywords . totonews_get_and_operator($categoryName) . '("' . $theCountry . '"' . ( $translation_country==$theCountry ? '' : ' "' . $translation_country . '")') . ')');

//            $articles_array[] = array(
//                'count' => 10,
//                'title' => __($location->continent, 'Continent'),
//                'category' => $categoryName,
//                'exclude' => $exclude,
//                'keywords' =>$keywords . totonews_query_keywords($translation_continent, 5, $categoryName));
//            skipToInternational1:
//            $articles_array[] = array(
//                'count' => $numOfNews,
//                'title' => $intTitle,
//                'category' => $categoryName,
//                'exclude' => $exclude,
//                'keywords' => $keywords);
//        }
//        elseif($categoryName==="Rice"){

//            $articles_array[] = array(
//                'count' => 50,
//                'title' => "",
//                'category' => $categoryName,
//                'exclude' => NULL,
//                'keywords' => "");
//        }
//        else
//        {

//            $catForContinent=$categoryName;
//            $catForCountry=$categoryName;
//            $keywordsForContinent=$keywords . totonews_query_keywords($location->continent, 5, $categoryName);
//            $keywordsForCountry=$keywords. totonews_get_and_operator($categoryName) . '("' . $theCountry . '"' . ( $translation_country==$theCountry ? '' : ' "' . $translation_country . '")') . ')';

//            if(stripos($location->country, "Bangladesh")!==false && $categoryName == "RSS - Bengali"){
//                $catForCountry="RSS - Bengali - Nation";
//                $keywordsForCountry=$keywords;
//            }

//            if(!empty($keywordsStFromSearchBox)){ goto skipToInternational2;}
//            //Local: city
//            $articles_array[] = array(
//                'count' => 30,
//                'category' => $categoryName,
//                'exclude' => NULL,
//                'title' => $translation_city,
//                'keywords' => $keywords. totonews_get_and_operator($categoryName) . '("' . $location->city . '"' . ( $translation_city!=$location->city ? (totonews_get_and_operator($categoryName) . '"' . $translation_city . '"') : '') . ')');
//            //Regional: Country
//            $articles_array[] = array(
//                'count' => 20,
//                'category' => $catForCountry,
//                'exclude' => NULL,
//                'title' => $translation_country,
//                'keywords' => $keywordsForCountry);
//            //continent - Africa, Asia
//            $articles_array[] = array(
//                'count' => 10,
//                'title' => __($location->continent, 'Continent'),
//                'category' => $catForContinent,
//                'exclude' => NULL,
//                'keywords' =>$keywordsForContinent);
//            skipToInternational2:
//            $articles_array[] = array(
//                'count' => $numOfNews,
//                'title' => $intTitle,
//                'category' => $categoryName,
//                'exclude' => NULL,
//                'keywords' => $keywords);
//        }
//        skipMain:
//        $prepared = false;

//        foreach ($articles_array as $c)
//        {
//            $articles = totonews_query($c['keywords'], $c['count'], $c['category'], $c['exclude']);
//            if( isset($articles) && $articles!==NULL && count($articles) > 0)
//            {
//                $output .= '<div><h4><b>' . __($c['title'], 'News') . '</b></h4>';

//                $output .= '</p>';
//                if($show_keywords && trim($c['keywords'])!='' && empty($keywordsStFromSearchBox))
//                    $output .= '<p><i>Keywords: ' . $c['keywords'] . '</i></p>';

//                $output .= '</div>';


//                foreach($articles as $article)
//                {
//                    if(empty($article->title) && strlen($article->link)<3){ goto nextArticle;}


//                    $sourceSt=trim($article->link);
//                    $sst=substr($sourceSt, 0, 5);
//                    if ($sst=="http:"){ $sourceSt=substr($sourceSt, 7); }
//                    else if ($sst=="https:"){ $sourceSt=substr($sourceSt, 8); }
//                    if(strpos($sourceSt, "/")>=0){$sourceSt=substr($sourceSt, 0, strpos($sourceSt, "/"));}

//                    if($categoryName == 'Google')
//                        $desSt = strip_tags(explode('<font size="-1">', $article->description)[2]);
//                    elseif($categoryName == 'Yahoo' && stripos($article->description, "<p>")!==false){
//                        //<p><a ..></a></p>
//                        $desSt = strip_tags(explode('<p>', $article->description)[1]);
//                        //Feb. 22, 2014, 4:22 a.m.                        
//                    }elseif(stripos($article->description, "<p>")!==false){
//                        //<p><a ..></a></p>
//                        $desSt=substr($article->description, 0, stripos($article->description, "</p>")-4);
//                        $desSt = strip_tags(explode('<p>', $desSt)[1]);

//                        //Feb. 22, 2014, 4:22 a.m.                        
//                    }else
//                        $desSt=$article->description;

//                    $imageURL = NULL;
//                    if($categoryName=='Google')
//                    {
//                        if(preg_match('~<img[^>]*src\s?=\s?[\'"]([^\'"]*)~i',$article->description, $imageURL)>0)
//                            $imageURL = $imageURL[1];
//                        else
//                            $imageURL = NULL;
//                        preg_match('@src="([^"]+)"@', $article->description, $match);
//                        //$article->description = shortdesc($article->description, $atts['length']);
//                    }
//                    if($categoryName == 'Yahoo')
//                    {
//                        if(preg_match('~<img[^>]*src\s?=\s?[\'"]([^\'"]*)~i',$article->description, $imageURL)>0){
//                            $imageURL = $imageURL[1];
//                            if(stripos($imageURL, "/http://")!==false){
//                                $imageURL=substr($imageURL, stripos($imageURL, "/http://")+1);
//                            }
//                            else if(stripos($imageURL, "/https://")!==false){
//                                $imageURL=substr($imageURL, stripos($imageURL, "/https://")+1);
//                            }
//                            //$desSt .= '------------'.$imageURL;
//                        }else
//                            $imageURL = NULL;
//                        preg_match('@src="([^"]+)"@', $article->description, $match);
//                        //$article->description = shortdesc($article->description, $atts['length']);

//                    }
//                    if( isset($article->enclosure) )
//                        $imageURL = $article->enclosure->attributes()->url;

//                    //Now we don't have the URL hard-coded

//                    $bing_ns = $article->children(totonews_bing_query(($c['keywords']!="" ? $c['keywords'] : NULL)));
//                    if(isset($bing_ns->Image) && trim($bing_ns->Image) !== '')
//                        $imageURL = $bing_ns->Image;

//                    //if( $article->children('News') && isset($article->children('News')->Image))
//                    //$imageURL = $article->children('News')->Image;

//                    $output .= '<div style="overflow: hidden">';
//                    $output .= '<p style="margin: 0 !important">';

//                    $title = htmlspecialchars(html_entity_decode(trim($article->title), ENT_QUOTES | ENT_XML1 ), ENT_QUOTES | ENT_XML1 );


//                    $output .= '<a target="_blank" rel="nofollow" href="' . htmlspecialchars($article->link, ENT_QUOTES | ENT_XML1) . '" style="color:red">' . $title . '</a>';
//                    $output .= '<br />';

//                    $date = strtotime($article->pubDate);
//                    $date = __(strftime("%A", $date), "Weather") . ', ' . __(strftime("%B", $date), 'Weather')  . ' ' . strftime("%e", $date) . ', ' . strftime("%G", $date);

//                    $output .= '<span>' . htmlspecialchars(html_entity_decode($sourceSt, ENT_QUOTES | ENT_XML1), ENT_QUOTES | ENT_XML1, $double_encode=false). ' | ' . __('Published on:', 'Weather') .' ' . $date . '</span></p>';
//                    if($imageURL !== NULL){
//                        $output .= '<img src="' . $imageURL . '" alt="" style="float: left; margin: 5px 5px 5px 5px; max-width: 80px; max-height: 80px;" />';
//                        //$output .= '<h4>Got image '.$imageURL.'!!!!!</h4>';
//                    }
//                    $desSt = htmlspecialchars_decode(html_entity_decode($desSt, ENT_QUOTES | ENT_HTML401), ENT_QUOTES | ENT_XML1);
//                    $desSt = trim($desSt);
//                    $desSt = totonews_clean_description($desSt);
//                    if(substr($desSt, -3)=="...")
//                        $desSt=substr($desSt, 0, strlen($desSt)-3);

//                    $desSt = htmlspecialchars($desSt, ENT_QUOTES | ENT_XML1);
//                    $link = htmlspecialchars(htmlspecialchars_decode($article->link, ENT_QUOTES | ENT_XML1), ENT_QUOTES | ENT_XML1);
//                    $output .= '<p style="margin: 0 !important">' . $desSt . '<a target="_blank" href="'. $link .'" style="color:blue">...</a></p>';
//                    $output .= '</div><br style="clear: both;" />';
//                    nextArticle:
//                }
//            }
//        }
//        $output .= '</div>';
//        return $output;
//    }
//    catch(Exception $e)
//    {
//        return '<div />';
//    }
//}
function totonews_query_keywords($keyword, $count=5, $category=NULL)
{
    global $wpdb;
    $keywords = $wpdb->get_var($wpdb->prepare('select `best_keywords` from `totonews_keywords` where `keyword_chain`=%s limit 1',$keyword));
    
    $output=totonews_format_keywordsWithSemiColon($keywords, $count, $category);
    
    return $output;
}

function totonews_format_keywordsWithSemiColon($keywords, $count=5, $category=NULL)
{
    $and_operator = totonews_get_and_operator($category);
    $or_operator = totonews_get_or_operator($category);
    
    $output = ""; //$and_operator . '(';

    
    
    if (!empty($keywords))
    {
        $keywords_array = preg_split('/ *\; */', $keywords);
        $keywords_array = array_slice($keywords_array, 0, $count);
        //echo "before:" .$keywords.'<br />';
        foreach($keywords_array as $specific_string)
            if(trim($specific_string)!='')
            {
                if( mb_stripos($specific_string, '+'))
                {
                    
                    $modified = '';
                    //foreach(explode($specific_string, '+') as $word)
                    //    $modified .= $or_operator . '"' . $word . '"';
                    
                    
                    $plus_array = preg_split('/ *\+ */', $specific_string);
                    $plus_array = array_slice($plus_array, 0, $count);
                    foreach($plus_array as $word){
                        
                        //foreach(explode($specific_string, '+') as $word){
                        
                        $modified .= ' '.$and_operator .  $word;
                    }
                    if($modified!=""){
                        $specific_string = '(' . trim($modified) . ')';
                    }
                    $output .= $or_operator.$specific_string.' ';
                }else{
                    $output .= $or_operator.'"'.$specific_string.'"'.' ';
                }
                //$output .= ($output===($and_operator . '(') ? '' : $or_operator) . '"' . $specific_string . '"';
                
                //for translation
                $translation = __($specific_string, 'News');
                if($translation!=$specific_string)
                    $output .= $or_operator . '"' . $translation . '"';
            }
        $output=trim($output);
    }
    //$output .= ')';
    //if(trim($output)===trim($and_operator . '()'))
    //    return '';
    //echo "final:" .$output.'<br />';
    return $output;
}

function totonews_output_to_string_buffer($x)
{
    global $totonews_sidebar_buffer;
    if(!isset($totonews_sidebar_buffer))
        $totonews_sidebar_buffer = '';
    $totonews_sidebar_buffer .= $x;
    return '';
}


function TotoNews_shortcode($atts){
    
    //originally: [TotoMenu var="3"]
    //now: [TotoNews]
    
    //return getSliderDiv();
	
	
    //echo " ".tototranslation_translate_to_language("Economy",  "News", "fra-fr");
    //echo tototranslation_translate_to_language("Economy",  "News", "spa-es");
    
    global $totonews_sidebar_buffer;
    $totonews_sidebar_buffer = '';
    ob_start('totonews_output_to_string_buffer');
    dynamic_sidebar('TotoNewsSidebar');
    ob_end_flush();
    
    extract(
        shortcode_atts(
            array('var' => '', 'js' => 'false'), $atts,
            array('searchWords' => '', 'js' => 'false'), $atts,
            array('search' => '', 'js' => 'false'), $atts,
            array('iso2' => '', 'js' => 'false'), $atts,
            array('language' => '', 'js' => 'false'), $atts
        )
    );

	$output='<style>h1.page-title { display: none !important; } </style>';
    //$output.=getSliderDiv();
    $output .= '<div id="totoNewsContent">'; //<h4>'.$var.'</h4>';  
    $lowestSt="";
    $searchWords="";
    if (!empty($_POST)) //symbol search 
    {
        $searchWords=$_POST["totonews_keywordsSearch"];
        $lowestSt=$searchWords;
        $var="";
        
    }else{
        
        $cat=explode('|', $var);
        $lowestSt=$cat[0];
    }
    if($searchWords==""){$searchWords=$search;}
    $output .= totonews_getNewsContentSt($var, $searchWords,$lowestSt, $iso2, $language );
    return $output;
}




add_action('wp_enqueue_scripts', 'TotoNews_enqueue_scripts_and_styles');

function TotoNews_enqueue_scripts_and_styles()
{
    if( !is_admin())
    {
        wp_enqueue_script('jquery');

        wp_enqueue_script('jfeed', plugins_url("build/dist/jquery.jfeed.js", __FILE__), array("jquery"), '1.0');
        wp_enqueue_script('jfeed-pack', plugins_url("build/dist/jquery.jfeed.pack.js", __FILE__), array("jquery", "jfeed"), '1.0');
        wp_enqueue_script('jquery-ui', plugins_url("js/jquery-ui-1.10.3.custom.min.js", __FILE__), array("jquery", "jfeed", "jfeed-pack"), '1.0');
        wp_enqueue_script('jquery-mousewheel', plugins_url("js/jquery.mousewheel.min.js", __FILE__), array("jquery", "jquery-ui", "jquery"), '1.0');
        wp_enqueue_script('jquery-kinetic', plugins_url("js/jquery.kinetic.min.js", __FILE__), array("jquery", "jquery-ui"), '1.0');
        //wp_enqueue_script('jquery-mobile', plugins_url('js/jquery.mobile-1.4.3.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('jquery-smoothdivscroll', plugins_url("js/jquery.smoothdivscroll-1.3-min.js", __FILE__), array("jquery", "jquery-ui", "jquery-mousewheel", "jquery-kinetic"), '1.0');
        //'jquery-mobile', 
        wp_enqueue_script('TotoNews', plugins_url("js/totonews.js", __FILE__), array('jfeed', 'jfeed-pack', 'jquery-ui', 'jquery-mousewheel', 'jquery-kinetic', 'jquery-smoothdivscroll'), '1.0');
        
        wp_enqueue_style('TotoNews', plugins_url("css/TotoNews.css", __FILE__));
        
        $isMobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'.
                    '|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
                    '|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT'] );
        global $translation_language;
        wp_localize_script('TotoNews', 'totonews', array(
            'news' => __('News', 'News'),
            'twitter' => __('Twitter', 'News'),
            'under' => __('Under construction', 'News'),
            'underconstructionimage' => plugins_url('icons/Under_Construction.jpeg', __FILE__),
            'mobile' => $isMobile
            ));
    }
}

//add_action('wp_ajax_nopriv_totonews_ajax_query', 'totonews_ajax_query');
//add_action('wp_ajax_totonews_ajax_query', 'totonews_ajax_query');

//function totonews_ajax_query()
//{
//    if(isset($_REQUEST) && (key_exists('atts', $_REQUEST)))
//    {
//        header('Content-Type: text/xml');
//        $keywords1 = base64_decode($_REQUEST['atts']['keywords1']);
//        $keywords2 = base64_decode($_REQUEST['atts']['keywords2']);
//        $category = base64_decode($_REQUEST['atts']['category']); 
//        $title = base64_decode($_REQUEST['atts']['title']);
//        echo totonews_get_news_from_one_category($keywords1, $keywords2, $category, $title);
//    }
//    wp_die();
//}
function in_arrayi($needle, $haystack) {
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}
global $arrOfButtonTexts;
function getPreferenceButtons($location){
    $arr=array(); 
    //worked for countries, hobbies, sports, topics, music
    //removed residents
    
    foreach($location->countries as $h){
        array_push($arr, $h);
    }
    
    foreach($location->hobbies as $h){
        array_push($arr, $h);
    }
    foreach($location->sports as $h){
        array_push($arr, $h);
    }
    
    foreach($location->professions as $h){
        array_push($arr, $h);
    }
    //foreach($location->languages_learning as $h){
    //    array_push($arr, $h);
    //}
    if(isset($location->topics) && is_array($location->topics))
        foreach($location->topics as $h){
            array_push($arr, $h);
        }
    foreach($location->roles as $h){
        array_push($arr, $h);
    }
    
    if(isset($location->future_roles) && is_array($location->future_roles))
        foreach($location->future_roles as $h){
            array_push($arr, $h);
        }
    foreach($location->movies as $h){
        array_push($arr, $h);
    }
    foreach($location->music as $h){
        array_push($arr, $h);
    }
    //foreach($location->languages as $h){
    //array_push($arr, $h);
    //}
    
    return $arr;
}

function testExists(){
    $arr=array();
    array_push($arr, "one");
    array_push($arr, "two");
    array_push($arr, "three");
    array_push($arr, "four");
    array_push($arr, "five");
    foreach($arr as $h){
        echo $h;
    }
    if (in_arrayi("ONE", $arr)==true){
        echo " check: yes";
    }else{
        echo "wrong";
    }
}

add_action('wp_ajax_nopriv_totonews_ajax_callLogSet', 'totonews_ajax_callLogSet');
add_action('wp_ajax_totonews_ajax_callLogSet', 'totonews_ajax_callLogSet');

function totonews_ajax_callLogSet()
{
    
    if(isset($_REQUEST) && (key_exists('atts', $_REQUEST)))
    {
        header('Content-Type: text/xml');
        
        $menuItem = esc_sql($_REQUEST['atts']['menuitem']); //base64_decode  'menuitem, currenturl 
        $currenturl = esc_sql($_REQUEST['atts']['currenturl']);
        totogeo_SetNavLog($menuItem, $currenturl);
        echo "<description>succeed</description>";
    }
    die();
}
?>