//var xhr;
//var xhr2;
//var selectedRowNum = 1;
//var selectedColNum = 1;//////////////////////

var totoNews_tokenAvailable = true;
var totoNews_oldSelectedIdSt = "";

jQuery(document).ready(function () {
   // if (!totonews.mobile)
        //jQuery(".totoNews_makeMeScrollablecls").smoothDivScroll({ mousewheelScrolling: "allDirections", touchScrolling: false, hotSpotScrolling: !totonews.mobile, visibleHotSpotBackgrounds: !totonews.mobile ? 'always' : '' });

    jQuery('[id^=totonews_show_moredata_]').click(function () {
        console.log("show more is clicked");
        var tmp = jQuery(this).attr('id');
        var show_id = tmp.substring(tmp.indexOf("moredata_") + 9);
        console.log("totonews_moredata_" + show_id + " is targeted");
        jQuery("#totonews_moredata_" + show_id).css("display", "inline");
        jQuery("#totonews_show_lessdata_" + show_id).css("display", "inline");
        jQuery("#totonews_show_moredata_" + show_id).css("display", "none");
        //jQuery(this).hide;
        console.log("Done done");
    });

    jQuery('[id^=totonews_show_lessdata_]').click(function () {
        console.log("show less is clicked");
        var tmp = jQuery(this).attr('id');
        var show_id = tmp.substring(tmp.indexOf("lessdata_") + 9);
        jQuery("#totonews_moredata_" + show_id).css("display", "none");
        jQuery("#totonews_show_moredata_" + show_id).show;
        jQuery("#totonews_show_lessdata_" + show_id).css("display", "none");
        jQuery("#totonews_show_moredata_" + show_id).css("display", "inline");
        //jQuery(this).hide;
        console.log("Done done");
    });

    jQuery('.totonews-button').click(function () {
        ////        cancelAjax();////
        var titleToShow = jQuery(this).attr('titletoshow');
        var idSt = jQuery(this).attr('id');

        var textInRowLan = jQuery(this).attr('textInRowLan');
        var categoryName = jQuery(this).attr('categoryname');
        var transLan = jQuery(this).attr('translan');

        var iso2 = jQuery(this).attr('iso2');
        var language = jQuery(this).attr('language');
        var headtextOnRow = jQuery(this).attr('headtextOnRow');
        //lancode, latitude,  longitude, keywordsForTweets
        var lancode = jQuery(this).attr('lancode');
        var latitude = jQuery(this).attr('latitude');
        var longitude = jQuery(this).attr('longitude');
        var keywordsForTweets = jQuery(this).attr('keywordsForTweets');


        var todaySt = jQuery(this).attr('today');
        var yesterdaySt = jQuery(this).attr('yesterday');

        //jQuery('[id^=totonewsButton_]').css("font-weight", "normal");
        jQuery('[id^=totonewsButton_]').css("background-color", "transparent");
        jQuery(this).css({ "background-color": "#CAE1C8" });


     
        buttonClicked8(headtextOnRow, titleToShow, "totonews_holder1", titleToShow, idSt, titleToShow, titleToShow, categoryName, transLan, iso2, language, textInRowLan, todaySt, yesterdaySt, lancode, latitude, longitude, keywordsForTweets);
    });

});


function totonews_scrollToAnchor() {

    var target = jQuery('[name=ttnewscontent]');
    if (target.length) {
        jQuery('html,body').animate({
            scrollTop: target.offset().top
        }, 100);
    }

}
function totonews_update_all_newsFor2() {
    var transCity = jQuery('.totonews-googlesmall').attr('titletoshow');
    var translan = jQuery('.totonews-googlesmall').attr('translan');

    //totonews_smallgooglenews --- div holder
    totonews_update_all_news2(transCity, 'totonews_smallgooglenews', '', '', transCity, '', translan, '', true, jQuery('#totoNewsCountryChoice').val(), "", "", "", "", "", "", "", "", "", 1);
    //////
}

function buttonClicked8(headtextOnRow, titleToShowInEnglish, totonewsHolderDiv, buttonText, idSt, kwords, kwordsForGoogle, category, theLan, iso2, language, textInRowLan, todaySt, yesterdaySt, lancode, latitude, longitude, keywordsForTweets) {
    //    alert("titleToShowInEnglish:" + titleToShowInEnglish);
    //    alert("id string:" + jQuery('#' + idSt).html());
    var menuItem = headtextOnRow + "|" + titleToShowInEnglish;
    totonews_logActivities(menuItem);

    buttonText = jQuery('#' + idSt).html();  // in translation

    jQuery("#totoNewsSearch").val(titleToShowInEnglish);

    jQuery('#tweetsDiv').html("");
    var kwords2 = "";

    kwords = titleToShowInEnglish; //in English
    kwords2 = buttonText;  //in translation

    kwordsForGoogle = kwords; //kwordsForGoogle);
    category = category;

    jQuery('#totonews_showLoadingImg').show();

    jQuery('#' + totoNews_oldSelectedIdSt).removeClass("totonewsbuttonselected");
    jQuery('#' + idSt).addClass("totonewsbuttonselected");
    totoNews_oldSelectedIdSt = idSt;


    if (kwords.indexOf(" ") != -1) { kwords = '"' + kwords + '"'; }
    if (kwordsForGoogle.indexOf(" ") != -1) { kwordsForGoogle = '"' + kwordsForGoogle + '"'; }
    if (kwords2.indexOf(" ") != -1) { kwords2 = '"' + kwords2 + '"'; }

    totonews_update_all_news2(titleToShowInEnglish, totonewsHolderDiv, buttonText, kwords, kwordsForGoogle, category, theLan, kwords2, false, iso2, language, textInRowLan, headtextOnRow, todaySt, yesterdaySt, lancode, latitude, longitude, keywordsForTweets, 1);
    //    setInterval(function () {
    //        // Do something every 5 seconds
    //    }, 5000);

}

//function totonews_update_all_news() { //Rice, Cattle (beef), Cattle (dairy), Maize, Soybeans


//    jQuery('#totonews_searchLoadingImg').hide();
//    var keywords1 = jQuery('#' + totonewsHolderDiv).attr('keywords1')
//    var keywords2 = jQuery('#' + totonewsHolderDiv).attr('keywords2')
//    var category = jQuery('#' + totonewsHolderDiv).attr('category')

//    totonews_update_ajax('totonews_ajax_query', keywords1, keywords2, category, "", "tag");

//    return;
//}


function totonews_update_all_news_old(pluginsUrl) { //Rice, Cattle (beef), Cattle (dairy), Maize, Soybeans
    jQuery('#totonews_searchLoadingImg').hide();
    //Rice|Crops|Agriculture
    var keywords1 = jQuery('#' + totonewsHolderDiv).attr('keywords1');


    //    if (keywords1 == "Rice|Crops|Agriculture") {

    //totonews_update_ajax('totonews_ajax_query', "", "", "Rice", "Rice Feeds", "tag");
    return;
    //    }



    //    for (var i = 1; i < 10; i++) {

    //        totonews_update_ajax('totonews_ajax_query', jQuery('#totonews_holder' + i).attr('keywords1'), jQuery('#totonews_holder' + i).attr('keywords2'), jQuery('#totonews_holder' + i).attr('category'), jQuery('#totonews_holder' + i).attr('title'), jQuery('#totonews_holder' + i).attr('tag'));
    //    }



    //Sean's old news
    //    jQuery('.totonews_holder').each(
    //        function (index) {
    //            totonews_update_ajax('totonews_ajax_query', jQuery(this).attr('keywords'), jQuery(this).attr('category'), jQuery(this).attr('title'), jQuery(this).attr('tag'), this);
    //        }
    //    );
}

//////function showAll() {


//////    if (jQuery('#totoNewsSearch').val() != "") { jQuery('#totonews_searchLoadingImg').show(); }

//////    var st = jQuery('#totonews_hiddenWhichOneShows').html();


//////    var arr = st.split(";")
//////    for (var i = 0; i < arr.length; i++) {
//////        //jQuery('#totonews_toc' + arr[i]).html(title);
//////        jQuery('#totonews-' + arr[i]).show();
//////    }
//////    jQuery('#totonews_showLoadingImg').hide();
//////}

//function totonews_update_ajax(action, keywordsFromButtons, keywordsFromSearchBox, category, title, tag) {

//    //    if (xhr && xhr.readyState > 0 && xhr.readyState < 4) {
//    //        // there is a request in the pipe, abort
//    //        xhr.abort();
//    //    }


//    //category is encoded, title is not encoded
//    var xhr = jQuery.ajax({
//        url: totolocation.ajaxurl,
//        data: {
//            'action': 'totonews_ajax_query',
//            'atts':
//                {
//                    'keywords1': keywordsFromButtons,
//                    'keywords2': keywordsFromSearchBox,
//                    'category': category,
//                    'title': ""
//                }
//        },
//        async: true, cache: false,
//        contentType: 'application/json; charset=utf-8',
//        success: function (result) {

//            var theNum = parseInt(jQuery('#totonews_hiddenNum').html());
//            jQuery('#totonews_hiddenNum').html(theNum + 1);

//            var content;
//            if (typeof window.XMLSerializer != 'undefined')
//                content = (new window.XMLSerializer()).serializeToString(result);
//            else
//                content = result.xml;



//            if (result == '' || content == '' || content == '<div/>' || content == '<div />') {
//                //                jQuery('.' + tag).html('');
//                //                jQuery('.' + tag).hide();
//                //                jQuery('#totonews_toc' + theNum).html('');
//                //                jQuery('#totonews-' + theNum).hide();
//                jQuery('#' + totonewsHolderDiv).html("no content");
//            }
//            else {
//                //totonews_hiddenWhichOneShows
//                //                var whichOnes = jQuery('#totonews_hiddenWhichOneShows').html();
//                //                whichOnes = whichOnes + ";" + theNum;
//                //                jQuery('#totonews_hiddenWhichOneShows').html(whichOnes);

//                //start
//                jQuery('#' + totonewsHolderDiv).html(content);


//                //                jQuery('#totonews_toc' + theNum).html(title);
//                //                jQuery('#gotoID' + theNum).show();

//                //                jQuery('#totonews_title' + theNum).html(title);
//                //                jQuery('#totonews_title' + theNum).show();
//            }
//            jQuery('#totoNews_searchTable').show();
//            jQuery('#totonews_showLoadingImg').hide();
//            //            if (theNum == 9) {
//            //                showAll();
//            //                jQuery('#totoNews_searchTable').show();
//            //            }
//            //            if (category == "Rice") {
//            //                showAll();
//            //                jQuery('#totoNews_searchTable').show();
//            //            }

//        },
//        error: function (errors) {
//            alert("fail");
//            var err;
//            if (typeof window.XMLSerializer != 'undefined')
//                err = (new window.XMLSerializer()).serializeToString(errors);
//            else
//                err = errors.xml;

//            jQuery('#totonews_showLoadingImg').hide();
//            jQuery('#' + totonewsHolderDiv).html("error:" + errors.xml);
//            return;
//            var theNum = parseInt(jQuery('#totonews_hiddenNum').html());
//            jQuery('#totonews_hiddenNum').html(theNum + 1);
//            //            jQuery('.' + tag).html('');
//            //            jQuery('.' + tag).hide();

//            jQuery('#totonews_toc' + theNum).html('');
//            jQuery('#totonews_toc' + theNum).hide();
//            jQuery('#totonews-' + theNum).hide();

//            //            jQuery('.' + tag).html('');
//            //            jQuery('.' + tag).hide();
//            if (theNum == 9) {
//                showAll();
//            }
//        }
//    });
//}

function decode_base64(s) {
    var e = {}, i, k, v = [], r = '', w = String.fromCharCode;
    var n = [[65, 91], [97, 123], [48, 58], [43, 44], [47, 48]];

    for (z in n) { for (i = n[z][0]; i < n[z][1]; i++) { v.push(w(i)); } }
    for (i = 0; i < 64; i++) { e[v[i]] = i; }

    for (i = 0; i < s.length; i += 72) {
        var b = 0, c, x, l = 0, o = s.substring(i, i + 72);
        for (x = 0; x < o.length; x++) {
            c = e[o.charAt(x)]; b = (b << 6) + c; l += 6;
            while (l >= 8) { r += w((b >>> (l -= 8)) % 256); }
        }
    }
    return r;
}

function formURLForTweets(textForTweets, kwords, category, theLan, lancode, latitude, longitude, keywordsForTweets, iso2) {
    
    //    alert(lancode);
    //    alert(latitude);
    //    alert(longitude);
    //    alert(keywordsForTweets);
    if (category != "") {
        kwords = "";
    }

    urlForTweets = "";
    if (textForTweets != "") {
        kwords = '"' + textForTweets + '"';
    }
    if (keywordsForTweets == "") { keywordsForTweets = kwords; }
    urlForTweets = '/wp-content/plugins/TotoNews/getTweets.php?q=' + encodeURI(keywordsForTweets); //  + escape(kwordsForGoogle);

    // lancode, latitude, longitude
    
    if (lancode != "") { urlForTweets = urlForTweets + "&lancode=" + encodeURI(lancode); }
    if (latitude != "") { urlForTweets = urlForTweets + "&latitude=" + encodeURI(latitude); }
    if (longitude != "") { urlForTweets = urlForTweets + "&longitude=" + encodeURI(longitude); }
    

    //alert("urlForTweets:" + urlForTweets);
    return urlForTweets;  //  + escape(kwordsForGoogle);
}  
function formURL(buttonText, kwords, kwordsForGoogle, category, theLan, kwords2, googleURL, iso2, language, urlPlanNum) {
    //urlPlanNum: 1=use our news server, and iso2; 2=use our news server without iso2;
    //            3=use google news with geocode;  4=use google news without geocode
    var theUrl = "";
    if (category != "") {
        kwords = "";
        kwords2 = "";
    }
    var selectedCountryName = jQuery('#totoNewsCountryChoice option:selected').text();


    if (urlPlanNum == 3 || urlPlanNum==4) {  //use google
        // if (kwordsForGoogle == "Swine / Pigs") { kwordsForGoogle = "Swine or Pigs"; }
        kwordsForGoogle = kwordsForGoogle.replace("/", " or ");
        

        //var gurlSt = escape('https://news.google.com/news/feeds?q=') + escape(kwordsForGoogle) + '&output=rss&geo=' + escape(selectedCountryName);

        //theUrl = '/wp-content/plugins/TotoNews/proxy.php?url=' + gurlSt;
        if (urlPlanNum==3) {  //use geocode
            theUrl = '/wp-content/plugins/TotoNews/proxy.php?keywords=' + escape(kwordsForGoogle) + '&geo=' + escape(iso2);
        } else {
            theUrl = '/wp-content/plugins/TotoNews/proxy.php?keywords=' + escape(kwordsForGoogle);
        }
//                alert("googleurl:" + theUrl);
        return theUrl;
        //        return '/wp-content/plugins/TotoNews/proxy.php?url=' + 'https://news.google.com/news/feeds?q=' + escape(kwordsForGoogle) + '&output=rss';

    }


    //if (category != "") { category.replace(/\{/g, " ").replace(/\}/g, " "); }


    //url: theurlPath & '/totoNews/rss-20.xml',   --- Solid Savings <Warning: appAPI.db storage is limited to 1000 bytes per key. For larger values please use appAPI.db.async Function-name: appAPI.db.set : key=_GPL_arbitrary_code> 
    //url: '/rss-20.xml',    ----- successful one
    //url: '/wp-content/plugins/TotoNews/xml/rss-20.xml',    ----- successful one
    //url: '/wp-content/plugins/TotoNews/proxy.php?url=http://topics.nytimes.com/top/news/international/countriesandterritories/tanzania/?rss=1',  //'http://topics.nytimes.com/top/news/international/countriesandterritories/tanzania/?rss=1',
    //url: news service



    var qSt = "";
    if (category != "") {
        if (qSt != "") { qSt = qSt + '&'; }

        qSt = qSt + 'query=' + escape(category.replace("/", " "));
    }
    else if (kwords2 != "") {
        qSt = 'query=' + escape(kwords2.replace("/", " ")) + '';  //encodeURIComponent
    }
    else if (buttonText != "") {
        qSt = 'query=' + escape('"' + buttonText.replace("/", " ")) + '"';  //encodeURIComponent
    } else if (kwords != "") {
        qSt = 'query=' + escape(kwords.replace("/", " ")) + '';  //encodeURIComponent, encodeURI
    }

    if (iso2 && urlPlanNum==1) { qSt = qSt + '&ISO2=' + iso2; }  //contain country code
    if (language) { qSt = qSt + '&language=' + language; }

    if (qSt != "") { qSt = qSt + '&'; }

    if (document.URL.indexOf("https:") != -1) {
        theUrl = 'https://news.totogeo.org/news/rss?' + qSt + 'count=50&tags=strip';
    } else {
        theUrl = 'http://news.totogeo.org/news/rss?' + qSt + 'count=50&tags=strip';

    }

   //  alert("normal:" + theUrl);
    return theUrl;
}
function findImg(description) {

    //<img alt="Volkswagen Golf R 400 Concept" data-credit="Volkswagen" data-mep="252383" src="http://o.aolcdn.com/hss/storage/midas/64647cdb62babc2f0bb19df4b8575825/200106270/golf-r-400-concept.jpg" />
    var imgSt = "";
    var divSt = description;
    var ind = divSt.indexOf("<img");
    while (ind != -1) {
        divSt = divSt.substring(ind + 4);

        ind2 = divSt.indexOf("/>");
        if (ind2 != -1) { divSt = divSt.substring(0, ind2); }

        ind3 = divSt.indexOf(" src=");
        if (ind3 != -1) {
            imgSt = divSt.substring(ind3 + 5).trim();

            var firstChar = imgSt.substring(0, 1);

            imgSt = imgSt.substring(0, imgSt.indexOf(firstChar + " ")).trim();
            imgSt = imgSt.substring(1);

            if (imgSt.indexOf("maps.google.com") != -1) { return ''; }
            return imgSt;
        }
        ind = divSt.indexOf("<img");
    }
    return '';
}


function totonews_sanitize(input) {
    return input.replace('&lt;br&gt;', ' ').replace(/\s\s+/, ' ').replace(/\\\\\\'/g, "'");
}

function totonews_update_all_news2(titleToShowInEnglish, totonewsHolderDiv, buttonText, kwords, kwordsForGoogle, category, theLan, kwords2, googleURL, iso2, language, textInRowLan, headtextOnRow, todaySt, yesterdaySt, lancode, latitude, longitude, keywordsForTweets, urlPlanNum) {
    //    if (buttonText != "") { alert("buttonText:" + buttonText); }

    //alert("iso2=" + iso2 + " language:" + language);
    if (todaySt == "") { todaySt = jQuery("#totonews_hiddenTodayTrans").html(); }
    if (yesterdaySt == "") { todaySt = jQuery("#totonews_hiddenYesterdayTrans").html(); }

    if (typeof headtextOnRow == 'undefined') {
        headtextOnRow = '';
    }

    if (typeof textInRowLan == 'undefined') {
        textInRowLan = "";
    }
    kwords2 = "";
    jQuery("#totonews_titleHolder").html("");
    //totonewsHolderDiv = 'totonews_titleHolder';

    var forFrontNews = false;
    var transWordForNews = jQuery('#hiddenNewsTransword').html();
    if (typeof transWordForNews == 'undefined') {
        transWordForNews = "";
        forFrontNews = true;
    }
    if (transWordForNews != "") { transWordForNews = transWordForNews + ": "; }


    jQuery('#' + totonewsHolderDiv).html("");
    jQuery('#totonews_showLoadingImg').show();
    //    if (xhr && xhr.readyState > 0 && xhr.readyState < 4) {
    //        // there is a request in the pipe, abort
    //        xhr.abort();
    //    }

    jQuery('#totonews_searchLoadingImg').hide();


    var theUrl = "";
    if (buttonText == "" && urlPlanNum != 4) {//use google
        urlPlanNum = 3;
        googleURL = true;
        theUrl = formURL(titleToShowInEnglish, kwords, kwordsForGoogle, category, theLan, kwords, googleURL, iso2, language, 3);
    } else {
        theUrl = formURL(titleToShowInEnglish, kwords, kwordsForGoogle, category, theLan, kwords, googleURL, iso2, language, urlPlanNum);
    }
    //theUrl = encodeURI(theUrl);
    //console.log(theUrl);
    //if (googleURL) { alert("this google url:" + theUrl); }
    totoNews_tokenAvailable = true;
    var xhr = jQuery.getFeed({
        url: theUrl,
        success: function (feed) {
            //            totonews_scrollToAnchor();
            var timeSt = "";
            if (!totoNews_tokenAvailable) { return; }
            totoNews_tokenAvailable = false;

            if (feed != undefined && feed.items) {

                jQuery('#totonews_showLoadingImg').hide();
                //alert("succeed!"+feed);
                //            jQuery('#result').append('<h2>'
                //            + '<a href="'
                //            + feed.link
                //            + '">'
                //            + feed.title
                //            + '</a>'
                //            + '</h2>');

                var html = '';

                //html += '<div>'+theUrl+'</div>'; // 

                //html = '<h2>' + transWordForNews + '</h2>';

                //html += "<h1>url:" + theUrl + "</h1>";
                var theContent = "";
                var preDes = "";
                var preTitle = "";
                var cct = 0

                //                if (googleURL) {
                //                    if (typeof feed.items == 'undefined') {
                //                        jQuery('#' + totonewsHolderDiv).html(html + "<right><img src='../img/icons/Under_Construction.jpeg'> Under Construction</right>");
                //                        return;
                //                    }

                //                }
                //                                alert(theUrl + ": " + feed.items.length);
                for (var i = 0; i < feed.items.length && cct < 50; i++) {
                    var item = feed.items[i];
                    if (item.title != "" && item.description != "~~~~" && item.link != "") {  //item.link != "" && 

                        var imgSrcSt = "";
                        //get description
                        var description = item.description;
                        //                        if (item.link.indexOf("idUSL3N0UV4CX20150116") != -1) {
                        //                            alert(item.link.indexOf("idUSL3N0UV4CX20150116") + "description:" + description + "   link="+item.link);
                        //                        }
                        if (typeof item.description == "undefined")
                            description = "";
                        else
                            description = totonews_sanitize(description.trim());

                        if (description != "" && description.indexOf("d=yIl2AUoC8zA") == -1 && description.indexOf("<img<a") == -1) { imgSrcSt = findImg(description); }
                        if (description != "") {
                            if (googleURL) {  // need strip html tags for the description

                                var divSt = description;
                                var ind = divSt.indexOf("<div");
                                while (ind != -1) {
                                    divSt = divSt.substring(ind + 4);
                                    ind = divSt.indexOf("<div");
                                    ind2 = divSt.lastIndexOf("</div>");
                                    if (ind2 != -1) { divSt = divSt.substring(0, ind2); }
                                }
                                var ind = divSt.indexOf('<font size="-1">');
                                if (ind != -1) {
                                    divSt = divSt.substring(ind);
                                    ind = divSt.indexOf("</font>");
                                    if (ind != -1) { divSt = divSt.substring(ind) + "</font>"; }
                                }

                                description = jQuery(divSt).text();

                            } else {

                                var ind = description.indexOf("<img");
                                if (ind != -1) { //remove <img from description
                                    //                          
                                    var descriptionSt = description.substring(0, ind);
                                    ind = description.indexOf(">");
                                    if (ind != -1) {
                                        description = descriptionSt + description.substring(ind + 1).trim();
                                    }
                                }

                                //description = jQuery(description).text();
                            }
                            description = shortenDes(description, item.link);
                        }


                        if (preTitle != item.title && preDes != description) {

                            preDes = description;
                            preTitle = item.title;

                            if (googleURL) {

                                theContent += getOneGoogleNewArticle(item.link, item.title, item.updated, description, imgSrcSt, todaySt, yesterdaySt);

                            } else {  //for regular

                                theContent += '<span style="font-weight:bold;font-size:16px;color:blue">';
                                if (item.link) {
                                    theContent += '<a href="' + item.link + '" target="_BLANK">' + totonews_sanitize(item.title) + '</a>';
                                } else {
                                    theContent += '<a>' + totonews_sanitize(item.title) + '</a>';
                                }
                                theContent += '</span>';
                                //the source and publication date
                                //add source
                                var source = item.link;


                                var theSSt = "";
                                var ind = item.link.indexOf("http://");
                                if (ind != -1) {
                                    theSSt = "http://";
                                } else {
                                    ind = item.link.indexOf("https://");
                                    if (ind != -1) {
                                        theSSt = "https://";
                                    } else {
                                        ind = item.link.indexOf("//");
                                        if (ind != -1) {
                                            theSSt = "//";
                                        }
                                    }
                                }

                                if (ind != -1 && theSSt != "") {
                                    source = source.substring(ind + theSSt.length);
                                    var ind2 = source.indexOf("/");
                                    if (ind2 != -1) { source = source.substring(0, ind2); }
                                }
                                source = source.trim();
                                if (source.substring(0, 4) == "www.") { source = source.substring(4).trim(); }

                                //source = "http://" + source;

                                if (source.indexOf("voanews.com") != -1) { source = "voa.org"; }

                                theContent += '<div>';
                                if (source != "") { theContent += '<span style="color:green">' + source + '</span>'; }
                                timeSt = convertToFormatedTime(item.updated, todaySt, yesterdaySt);
                                if (timeSt != "") {
                                    theContent += '<span> | ' + timeSt + '</span></div>';
                                }


                                if (imgSrcSt != "") { theContent += '<img src="' + imgSrcSt + '" alt="" style="float: left; margin: 5px 5px 5px 5px; max-width: 80px; max-height: 80px;" />'; }
                                //description
                                if (description != "") {
                                    theContent += '<div>' + description + '</div>';
                                }
                                theContent += '<br style="clear: both;" />';

                            }

                            cct = cct + 1;


                        } //end if title is not empty, and not same as before (title and description)
                    } //end if item.title is not empty
                } //end for loop
                if (theContent == '' || cct == 0) {
                    if (buttonText == '' && urlPlanNum == 3) { //for searching, special case for now: use google news with geocode
                        totonews_update_all_news2(titleToShowInEnglish, totonewsHolderDiv, '', kwords, kwordsForGoogle, category, theLan, "", true, iso2, language, textInRowLan, headtextOnRow, todaySt, yesterdaySt, lancode, latitude, longitude, keywordsForTweets, 4);
                        return;
                    }
                    else if (urlPlanNum == 1) {  //1 is already done at this point, run 2: =use our server without iso2
                        totonews_update_all_news2(titleToShowInEnglish, totonewsHolderDiv, buttonText, kwords, kwordsForGoogle, category, theLan, kwords2, false, iso2, language, textInRowLan, headtextOnRow, todaySt, yesterdaySt, lancode, latitude, longitude, keywordsForTweets, 2);
                        return;
                    } else if (urlPlanNum == 2) { //run 3 = use google news with geocode
                        totonews_update_all_news2(titleToShowInEnglish, totonewsHolderDiv, buttonText, kwords, kwordsForGoogle, category, theLan, "", true, iso2, language, textInRowLan, headtextOnRow, todaySt, yesterdaySt, lancode, latitude, longitude, keywordsForTweets, 3);
                        return;
                    } else if (urlPlanNum == 3) { //run 4 = use google news without geocode
                        totonews_update_all_news2(titleToShowInEnglish, totonewsHolderDiv, buttonText, kwords, kwordsForGoogle, category, theLan, "", true, iso2, language, textInRowLan, headtextOnRow, todaySt, yesterdaySt, lancode, latitude, longitude, keywordsForTweets, 4);
                        return;
                    } else {  //last one, already urlPlanNum==4
                        showNothingResults(totonewsHolderDiv);
                    }
                } else {
                    html = '<details open="open"><summary>'; // +decodeURIComponent(headtextOnRow.replace(/\+/g, " ")) + ' - ' + decodeURIComponent(buttonText.replace(/\+/g, " ")) + '</summary><div style="padding: 5px" id="totonews_titleHolder">' + theContent + '</div></details><br />';
                    var stInSummary = buttonText;
                    if (stInSummary == "") {
                        stInSummary = titleToShowInEnglish;
                    }
                    //                    html = '<details open="open"><summary>'; // +decodeURIComponent(headtextOnRow.replace(/\+/g, " ")) + ' - ' + decodeURIComponent(buttonText.replace(/\+/g, " ")) + '</summary><div style="padding: 5px" id="totonews_titleHolder">' + theContent + '</div></details><br />';
                    if (headtextOnRow != "" && headtextOnRow != undefined) {
                        html += decodeURIComponent(headtextOnRow.replace(/\+/g, " ")) + ' - ';
                    }
                    html += decodeURIComponent(stInSummary.replace(/\+/g, " ")) + '</summary><div style="padding: 5px" id="totonews_titleHolder">' + theContent + '</div></details><br />';

                    //jQuery("#totonews_titleHolder").html(html);

                    //html += theContent;
                    jQuery('#' + totonewsHolderDiv).html(html);
                    if (latitude == "" || longitude == "") {
                        //totonews_hiddenLatitude, totonews_hiddenLongitude
                        latitude = jQuery("#totonews_hiddenLatitude").val();
                        longitude = jQuery("#totonews_hiddenLongitude").val();
                        //alert("lat:" + latitude + "  longi:" + longitude);
                    }
                    if (textInRowLan == "") {
                        totonews_getTweets(totonewsHolderDiv, buttonText, kwords, kwordsForGoogle, category, theLan, kwords2, googleURL, cct, textInRowLan, buttonText, titleToShowInEnglish, 2, lancode, latitude, longitude, keywordsForTweets, iso2)

                    } else {
                        totonews_getTweets(totonewsHolderDiv, textInRowLan, kwords, kwordsForGoogle, category, theLan, kwords2, googleURL, cct, textInRowLan, buttonText, titleToShowInEnglish, 1, lancode, latitude, longitude, keywordsForTweets, iso2)
                    }
                }

            } else {
                showNothingResults(totonewsHolderDiv);
            }


        },
        error: function (errors) {
            //            alert("error!" + errors);
            jQuery('#' + totonewsHolderDiv).html("Error Occured!");
            jQuery('#totonews_showLoadingImg').hide();

        },
        timeout: 5000 // sets timeout to 5 seconds
    });
}

function showNothingResults(totonewsHolderDiv) {

    jQuery('#totonews_showLoadingImg').hide();
  
    jQuery('#' + totonewsHolderDiv).html('<span style="margin-left: .2em">No results.</span>');

//    jQuery('#' + totonewsHolderDiv).html('<img src="' + totonews.underconstructionimage + '"/><span style="margin-left: .2em">' + totonews.under + '</span>');
            

}
function totonews_getTweets(totonewsHolderDiv, textForTweets, kwords, kwordsForGoogle, category, theLan, kwords2, googleURL, cct, textInRowLan, buttonText, titleToShowInEnglish, whichTimeToRun, lancode, latitude, longitude, keywordsForTweets, iso2) {
   
    //search "textInRowLan" first, then "buttonText" (in translan), and the final one is "titleToShowInEnglish"

    /*************** For Tweets **************************/
    cct = cct + 3;
    //    if (xhr2 && xhr2.readyState > 0 && xhr2.readyState < 4) {
    //        // there is a request in the pipe, abort
    //        xhr2.abort();
    //    }
    var theUrl = formURLForTweets(textForTweets, kwords, category, theLan, lancode, latitude, longitude, keywordsForTweets, iso2);

    var xhr2 = jQuery.getFeed({
        url: theUrl,
        success: function (feed) {
            if (feed != undefined && feed.items && feed.items.length != 0) {
                jQuery('#totonews_showLoadingImg').hide();
                var htmlSt = '';

                for (var i = 0; i < feed.items.length && i < cct; i++) {
                    var item = feed.items[i];
                    htmlSt += item.description;


                }

                if (htmlSt != "") {
                    //                    htmlSt = '<div><details open="open"><summary>' + totonews.twitter + " (" + textForTweets + ')</summary><div style="padding: 5px">' + htmlSt + "</div></details></div>";
                    htmlSt = '<div><details open="open"><summary>' + totonews.twitter + '</summary><div style="padding: 5px">' + htmlSt + "</div></details></div>";
                    jQuery('#tweetsDiv').html(htmlSt);
                    jQuery('#' + totonewsHolderDiv).css({ "width": "66%" });
                }
                else {
                    jQuery('#' + totonewsHolderDiv).css({ "width": "100%" });
                }
            }
       
            else {
                hideTweets(totonewsHolderDiv);
                //jQuery('#tweetsDiv').html(html + "<right><img src='../img/icons/Under_Construction.jpeg'> Under Construction</right>");
            }

        },
        error: function (errors) {
            //            alert("error!" + errors);
            jQuery('#' + totonewsHolderDiv).html("Error Occured!");
            jQuery('#totonews_showLoadingImg').hide();
            jQuery('#tweetsDiv').html("Empty");

        }
    });

    /*************** End Tweets *************************/
    return;
}
function hideTweets(totonewsHolderDiv) {
    jQuery('#totonews_showLoadingImg').hide();
    jQuery('#tweetsDiv').html("");
    jQuery('#' + totonewsHolderDiv).css({ "width": "100%" });
}

function getOneGoogleNewArticle(theLink, theTitle, pubDate, theDescription, imgSrcSt, todaySt, yesterdaySt) {
//    alert("imgSrcSt:" + imgSrcSt);

    
    var st = '<div style="overflow: hidden"><p style="margin: 0 !important">';
    st += '<a target="_blank" rel="nofollow" href="' + theLink + '" style="color:blue">' + theTitle + '</a>';

    pubDate = convertToFormatedTime(pubDate, todaySt, yesterdaySt); //for google, for now, only English

    st += '<br /><span style="color:green">news.google.com</span>'; //Published on:
    if (pubDate != "") { st += '<span style="color:black">' + ' | ' + pubDate + '</span>'; } //Published on: 
  

    st += '</p>';
    if (imgSrcSt != "") { st += '<img src="' + imgSrcSt + '" alt="" style="float: left; margin: 5px 5px 5px 5px; max-width: 80px; max-height: 80px;" />'; }
    st += '<p>' + theDescription + '</p>';
    st += '</div><br style="clear: both;" />';

    imgSrcSt = "";
    return st;
}
function shortenDes(desSt, link) {

    if (desSt.indexOf("<a") != -1) { return desSt; }
    if (desSt.length < 150) { return desSt; }

    desSt = desSt.substring(0, 150);

    desSt = desSt.substring(0, desSt.lastIndexOf(" "));

    var linkSt = '<a href="' + link + '" target="_BLANK">&nbsp;...&nbsp;</a>';
    return desSt + linkSt;

}



function totonews_newDoc(iso2) {
    jQuery('#totonews_showLoadingImg').show();
    jQuery('#tweetsDiv').html("");
    jQuery('#totonews_holder1').html("");

    theUrl = document.URL;
    theUrl = theUrl.substring(0, theUrl.indexOf("?"));
    theUrl = theUrl + '?var=News&iso2=' + iso2;
    window.location.assign(theUrl)
}
function searchboxClicked() {
    
    var iso2 = jQuery('#totoNewsCountryChoice').val();

    //start to get news
    jQuery('#' + totoNews_oldSelectedIdSt).removeClass("totonewsbuttonselected");

    jQuery('[id^=totonewsButton_]').css("background", "");

    var sWord = jQuery("#totoNewsSearch").val();
    var countrySelected = jQuery("#totoNewsCountryChoice option:selected").text();
    
    var menuItem = countrySelected + "|" + sWord;
    totonews_logActivities(menuItem);
    
    jQuery('[titletoshow="' + escape(sWord) + '"]').css("background", "#D8D8D8");
   
    searchval = sWord.substr(0, 1).toUpperCase() + sWord.substr(1);
    jQuery('[titletoshow="' + escape(searchval) + '"]').css("background", "#D8D8D8");

    jQuery('#tweetsDiv').html("");
    totonews_update_all_news2(sWord, "totonews_holder1", '', sWord, sWord, "", "English", "", false, iso2, "", "", "", "", "", "", "", "", "", 1);
}
function searchKeyPress(e) {
    // look for window.event in case event isn't passed in
    if (typeof e == 'undefined' && window.event) { e = window.event; }
    if (e.keyCode == 13) {
        //searchboxClicked();
        document.getElementById('totoNewsSearchButton').click();
    }
}

function ConvertToTwoLetters(num) {

    var st = num.toString();

    if (st.length == 1) { st = "0" + st; }

    return st;
}

function convertToFormatedTime(thePubDateSt, todaySt, yesterdaySt) {
    if (thePubDateSt == "") { return ""; }


    //if thePubDate is more than one day beyond today, do not display pub-data (return empty string)
    var pubDate = new Date(thePubDateSt); //"1/16/2015"); //"2010-04-01 PST");  
    curDate = new Date();

//    var diff = Math.floor(curDate.getTime() - pubDate.getTime());
//    var day = 1000 * 60 * 60 * 24;
//    var days2 = Math.floor(diff / day);

    diff = new Date(curDate - pubDate);
    days = diff / 1000 / 60 / 60 / 24;

//    alert("thePubDateSt:" + thePubDateSt);
//    alert("month:" + pubDate.getMonth());
//    alert("dd:" + pubDate.getDate());
//    alert("days:" + days);
//    alert("days2:" + days2);
    if (days < -2) { //more than one day beyond "Today", return empty string (do not display pub date)
        return "";
    }

    //convert thePubDateSt: "Today", "Yesterday", and new format for other country "DD/MM/YYYY"
    var dateSt = "";
    if (days <= 1) {//thePubDate is today
        //        dateSt = todaySt;
      
        return todaySt;
    } else if (days <= 2) {//thePubDate is yesterday
        dateSt = yesterdaySt;
    } else {
        var mm = pubDate.getMonth() + 1;
        var dd = pubDate.getDate();
        var yy = pubDate.getFullYear();
        if (jQuery('#totoNewsCountryChoice').val() == "US") {
            dateSt = ConvertToTwoLetters(mm) + "/" + ConvertToTwoLetters(dd) + "/" + yy;
        } else {
            dateSt = ConvertToTwoLetters(dd) + "/" + ConvertToTwoLetters(mm) + "/" + yy;
        }
    }
    //for time string
    var timeSt = ""
    if (thePubDateSt.indexOf("12:00:00 AM") == -1 && thePubDateSt.indexOf("12:00:00 am") == -1) {
        var h = pubDate.getHours();  // 0-24 format
        var m = pubDate.getMinutes();
        var s = pubDate.getSeconds();
        if (h != 0 || m != 0 || s != 0) {
            timeSt = ConvertToTwoLetters(h) + ":" + ConvertToTwoLetters(m) + ":" + ConvertToTwoLetters(s);
        }
    }

    var theConvertedPubDate = dateSt + " " + timeSt;

    return theConvertedPubDate.trim();
}


function totonews_logActivities(menuItem) { //headtextOnRow
    //    alert("menuitem:" + menuItem);
    //insert an entry into [mobile_log_site_nav] by using ajax: menuItem, currenturl
    var currenturl = document.URL;
    var ind1 = currenturl.indexOf("?");
    if (ind1 != -1) { currenturl = currenturl.substring(0, ind1); }

    totonews_callLogSetting(menuItem, currenturl)
}

function totonews_callLogSetting(menuitem, currenturl) {

    //menuitem, currenturl
    jQuery.ajax({
        url: totolocation.ajaxurl,
        data: {
            'action': 'totonews_ajax_callLogSet',
            'atts':
                {
                    'menuitem': menuitem,
                    'currenturl': currenturl
                }
        },
        async: true, cache: false,
        contentType: 'application/json; charset=utf-8',
        success: function (result) {
            //            alert("yes");
            var content;

            if (typeof window.XMLSerializer != 'undefined')
                content = (new window.XMLSerializer()).serializeToString(result);
            else
                content = result.xml;

            if (content != "") {
                //                var returnText = content.replace("<description>", "").replace("</description>", "").trim();
            }
        },
        error: function (errors) {
            //                        alert("error:" + errors);
        }
    });
}

//myScroll1= new iScroll("wrapper1", {
//		snap: "td",
//		momentum: true,
//		hScrollbar: false
//		});	

//http://stackoverflow.com/questions/10943544/how-to-parse-a-rss-feed-using-javascript
//jQuery.get(FEED_URL, function (data) {
//    jQuery(data).find("entry").each(function () { // or "item" or whatever suits your feed
//        var el = jQuery(this);

//        console.log("------------------------");
//        console.log("title      : " + el.find("title").text());
//        console.log("author     : " + el.find("author").text());
//        console.log("description: " + el.find("description").text());
//    });
//});

//function cancelAjax() {
//  
//    
//    if (xhr && xhr.readyState > 0 && xhr.readyState < 4) {
//        // there is a request in the pipe, abort
//        xhr.abort();
//        
//    }

//    if (xhr2 && xhr2.readyState > 0 && xhr2.readyState < 4) {
//        // there is a request in the pipe, abort
//        xhr2.abort();
//        
//    }
//    
//}