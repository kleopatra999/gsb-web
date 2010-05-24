<?php

//  mirror randomizer for <http://mirrors.gnomeslackbuild.org>
//
//  Needs the following mod_rewrite rules:
//
//  RewriteBase /
//  RewriteCond %{REQUEST_FILENAME} !-f
//  RewriteCond %{REQUEST_FILENAME} !-d
//  RewriteRule . /index.php [L]
//

// define mirrors and paths here (no trailing slash!)
// last digit in the arrays are "weight" in the random pool - hi number =
// more rotations in the random pool
$sites[0]  = array("http://slackware.org.uk/html", 5);
$sites[1]  = array("http://darkstar.ist.utl.pt/slackware/addon/gnomeslackbuild",0);
$sites[2]  = array("http://mirror.switch.ch/ftp/mirror/gsb",0);
$sites[3]  = array("http://mirrors.dotsrc.org/gsb", 0);
$sites[4]  = array("http://ftp.slackware.pl/pub/gnomeslackbuild/gsb", 0);
$sites[5]  = array("http://slackware.rol.ru/gsb/gsb", 0);
$sites[6]  = array("http://get.gnomeslackbuild.org/gsb", 0);
// end vars

$countsites = count($sites);
for($i=0; $i<$countsites; $i++)
{
    for($x=0; $x<$sites[$i][1]; $x++)
    {
        $mylist[] = array($sites[$i][0]);
    }
}
$countlist = count($mylist);
$countlist = $countlist - 1;
$picker = rand(0, $countlist);
$picked = $mylist[$picker][0];
$mirror = $picked;

// get their request
$req_uri = $_SERVER['REQUEST_URI'];

// Redirect!
# debug
# echo ($mirror.$req_uri);
header("Location: $mirror"."$req_uri",TRUE,301);
exit ();

?>
