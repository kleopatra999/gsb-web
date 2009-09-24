<?php

/* versions func */
/* see <root>/web/versions.txt for info */

// global, req'd stuff
$versions = @file("versions.txt");
$proj_ver = explode(" : ", trim($versions[0]));

$gsb_source_ver       =   $proj_ver[0];
$gsb_bin_stable_ver   =   $proj_ver[1];
$gsb_bin_stable       =   $proj_ver[2];
$slack_ver            =   $proj_ver[3];
$slack_unstable_ver   =   $proj_ver[4];
$gsb_bin_64_ver       =   $proj_ver[5];
$gsb_bin_current_ver  =   $proj_ver[6];
$gsb_bin_unstable_ver =   $proj_ver[7];
$gsb_bin_update_ver   =   $proj_ver[8];
$gsb_slax_ver         =   $proj_ver[9];
$slax_ver             =   $proj_ver[10];

// net-installer defaults
if(!isset($arch))
{
    $arch = "gsb";
}
if(!isset($use_ver))
{
    $use_ver = $gsb_bin_stable;
}

// slapt-get version extractor
if(!isset($arch_path))
{
    $arch_path = "gsb";
}
if(!isset($slack_arch))
{
    $slack_arch = "slackware";
} 
$slapt_path =
        "/home/chipster/rsync_repos/gsb/gsb/".$arch_path."-".$use_ver."_".$slack_arch."-".$slack_ver."/".$arch_path."/ad/";
$slapt_ver_cmd = "ls $slapt_path/slapt-get*.txz | \
        sed s'|\/.*\/.*\/.*\/||'g | sed s'|slapt-get-||'g | sed \
        s'|\.txz||g'";
$slapt_get_ver = @exec($slapt_ver_cmd);

?>

