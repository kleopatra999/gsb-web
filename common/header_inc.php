<?php
/*
    Header PHP script
    Chip Cuccio <http://chip.cuccio.us>
    $Id: header_inc.php 3094 2007-12-09 21:42:08Z chipster $
*/

/* set preliminary headers */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Expires now
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// MIME negotiation
require('common/mime_inc.php');

// force non-caching CSS after changes
$CSS_RAND = true;

?>
  <head>
    <!--
    GSB site code <http://gnomeslackbuild.org>
    by Chip Cuccio <http://chip.cuccio.us>
    $Date: 2007-12-24 10:33:44 -0800 (Mon, 24 Dec 2007) $
    $Rev: 3286 $
    
    // ENVs:

        OP:       <?php echo($op."\n"); ?>
        REQ URI:  <?php echo($_SERVER['REQUEST_URI']."\n"); ?>
        QS:       <?php echo($_SERVER['QUERY_STRING']."\n"); ?>

    // VERs:
              <?php print("\n\t"
                        ."GSB-source-ver:\t\t\t".$gsb_source_ver."\n\t"
                        ."GSB-bin-stable-ver (web):\t".$gsb_bin_stable_ver."\n\t"
                        ."GSB-bin-stable-ver (installer):\t".$gsb_bin_stable."\n\t"
                        ."Slack-stable-ver:\t\t".$slack_ver."\n\t"
                        ."Slack-unstable-ver:\t\t".$slack_unstable_ver."\n\t"
                        ."GSB-bin-64-ver:\t\t\t".$gsb_bin_64_ver."\n\t"
                        ."GSB-bin-current-ver:\t\t".$gsb_bin_current_ver."\n\t"
                        ."GSB-bin-unstable-ver:\t\t".$gsb_bin_unstable_ver."\n\t"
                        ."Slapt-Get-ver:\t\t\t".$slapt_get_ver_noarch."\n\t"
                        ."GSB-Slax-ver:\t\t\t".$gsb_slax_ver."\n\t"
                        ."Slax-stable-ver:\t\t".$slax_ver."\n\t\n"); ?>
    -->
    <meta http-equiv="Content-Type"
          content="<?php echo $mime ?>; charset=utf-8; ?>" />
    <meta name="author"
          content="Chip Cuccio" />
    <meta name="keywords"
          content="gsb,GSB,GNOME,gnome,SlackBuild,GNOME SlackBuild,Slack,Slackware,Slackware Linux,Linux" />
    <meta name="description"
          content="GNOME SlackBuild (GSB) - a GNOME distribution for Slackware Linux" />
    <style type="text/css"
          title="Chipster&#39;s Blue and Plenty-O-Whitespace Style">
    /*<![CDATA[*/
    <?php if($CSS_RAND == true) { ?>
    @import url('/css/style.css?v=<?php echo($rnd); ?>') screen;
    <?php } else { ?>
    @import url(/css/style.css) screen;
    <?php } ?>
    /*]]>*/
    </style>
    <link rel="stylesheet"
    href="/css/print.css?v=<?php echo($rnd); ?>"
          type="text/css"
          media="print" />
    <link rel="shortcut icon"
          href="/favicon.ico" />
    <meta name="MSSmartTagsPreventParsing"
          content="TRUE" />
    <meta name="MSThemeCompatible"
          content="No" />
    <meta http-equiv="imagetoolbar"
          content="no" />
    <meta name="robots"
          content="noarchive" />
    <title>
      <?php echo($title); ?> - a GNOME distribution for Slackware Linux
    </title>
    <link rel="stylesheet"
          href="/js/lightbox/css/lightbox.css"
          type="text/css"
          media="screen" />
    <script type="text/javascript"
          src="/js/lightbox/js/prototype.js">
    </script>
    <script type="text/javascript"
          src="/js/lightbox/js/effects.js">
    </script>
    <script type="text/javascript"
          src="/js/lightbox/js/lightbox.js">
    </script>
  </head>
  <body>
    <?php include('content/header.html'); ?>
