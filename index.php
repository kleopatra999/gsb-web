<?php
/*
    Chip Cuccio <http://chip.cuccio.us>
    $Id: index.php 2773 2007-11-18 16:49:41Z chipster $
*/

require('common/versions_inc.php');

// Set req. vars
$errno          =   $_REQUEST['errno'];
$op             =   $_REQUEST['op'];
$ports          =   $_REQUEST['ports'];
$news_page      =   $_REQUEST['news_page'];
$http_error     =   $_REQUEST['http_error'];
$cl_ver         =   $_REQUEST['cl_ver'];

// Set the default index section if none specified or on initial page entry
if (!isset($op))
{
    $op = "index";
}

// common stuff 
$rnd = md5(gmdate('M d Y'));
$common_title = "GNOME SlackBuild";

// error handlers
switch ($errno)
{
    case "401":
        $errdesc = "Error 401 - Authorization Required";
        break;
    case "403":
        $errdesc = "Error 403 - Access Forbidden";
        break;
    case "404":
        $errdesc = "Error 404 - Not Found";
        break;
}

// Section title display engine
switch ($op)
{ 
    case "index":
        if (strstr($_SERVER['REQUEST_URI'], "news"))
        {
            $title = "News : $common_title";
        }
        else
        {
            $title = $common_title;
        }
        break;
    case "about":
            $title = "About : $common_title";
        break;
    case "download":
        if ($ports == true)
        {
            $title = "Ports : $common_title";
        }
        else
        {
            $title = "Download &amp; Installation : $common_title";
        }
        break;
    case "links":
        $title = "Links : $common_title";
        break;
    case "software";
        $title = "Software Included with GSB : $common_title";
        break;
    case "whatsnew";
        $title = "What's New in GSB : $common_title";
        break;
    case "screenshots";
        $title = "Screenshots : $common_title";
        break;
    case "support":
        $title = "Support : $common_title";
        break;
    case "development":
        $title = "Development : $common_title";
        break;
    case "configure":
        $title = "Configuring GSB : $common_title";
        break;
    case "changelog":
        $title = "ChangeLog : $common_title";
        break;
    case "lists":
        $title = "Mailing Lists : $common_title";
        break;
     case "slax":
        $title = "GSB for Slax : $common_title";
        break;
    case "search":
        $title = "Search : $common_title";
        break;
    case "http_error":
        $title = $errdesc;
        break;
    default:
        $title = "$common_title";
        header("Location: /");
        exit();
}

// The "engine" that runs the site ops.
function section($op,$news,$errno,$errdesc,$SERVER_NAME,$HTTP_REFERER,
                 $SERVER_SIGNATURE,$REQUEST_URI,$REDIRECT_URL,$mode,
                 $gsb_bin_update_ver,$slack_ver,$dir,$path,$ver,
                 $gsb_bin_stable_ver,$gsb_source_ver,$cl_ver,$ports,
                 $stable,$gsb_bin_unstable_ver,$gsb_bin_current_ver,
                 $gsb_bin_stable,$slack_unstable_ver,$gsb_bin_64_ver,
                 $gsb_slax_ver,$slax_ver,$slapt_get_ver)
{
    $PAGE['url_'.$op] = ("content/$op.html");
    $doit = "url"."_"."$op";
    if (is_file($PAGE[$doit]))
    {
        include($PAGE[$doit]);
    }
    return $section;
}

// common includes
include('common/header_inc.php');
include('common/body_inc.php');
include('common/footer_inc.php');

?>
