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
$sites[1]  = array("http://darkstar.ist.utl.pt/slackware/addon/gnomeslackbuild",0); #!! bad dir layout
$sites[2]  = array("http://mirror.switch.ch/ftp/mirror",5); # good
$sites[3]  = array("http://mirrors.dotsrc.org/gsb", 0); #!! outdated
$sites[4]  = array("http://ftp.slackware.pl/pub/gnomeslackbuild", 5); # good
$sites[5]  = array("http://slackware.rol.ru/gsb", 5); # good
$sites[6]  = array("http://get.gnomeslackbuild.org", 2); #good
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

# debug
# echo ($mirror.$req_uri);

// the logic here looks for a "/gsb/" (our std. dir/repo parent layout) and
// adds it to the redir if non-existant.
if (!preg_match("/\/gsb/", $req_uri)) # "/gsb" missing

{
    # request is missing "/gsb/" path - we add it to the redirect
    header("Location: $mirror"."/gsb"."$req_uri", TRUE, 301);
    exit();
}
else
{
    # request contains proper "/gsb/" path - we redirect normally
    header("Location: $mirror"."$req_uri", TRUE, 301);
    exit();
}

?>
