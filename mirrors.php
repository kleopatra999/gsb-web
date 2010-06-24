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
$sites[0]  = array("http://slackware.org.uk", 5); # good
$sites[1]  = array("http://mirror.switch.ch/ftp/mirror",5); # good
$sites[2]  = array("http://ftp.slackware.pl/pub/gnomeslackbuild", 5); # good
$sites[3]  = array("http://slackware.rol.ru/gsb", 5); # good
$sites[4]  = array("http://get.gnomeslackbuild.org", 1); #good
$sites[5]  = array("http://ftp5.gwdg.de/pub/linux/slackware", 5); #good
$sites[6]  = array("http://mirrors.dotsrc.org", 5); #good
$sites[7]  = array("http://ftp.pnfi.kemdiknas.go.id", 2); #good
$sites[8]  = array("http://ftp.osuosl.org/pub", 15); #good
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
