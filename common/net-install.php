<?php

$ident = '$Id: net-install.php 3276 2007-12-23 00:02:46Z chipster $';
// prevent confusion - wheee!
$ident = str_replace(".php", "", $ident);

// op
$use_ver = $_REQUEST['use_ver'];
// arch
$arch = $_REQUEST['arch'];
switch ($arch)
{
    case "gsb":
        $arch_path = "gsb-";
        break;
    case "gsb64":
        $arch_path = "gsb64-";
        break;
    default:
        $arch = "gsb";
        $arch_path = "gsb-";
}

// need version vars
require('versions_inc.php');

// slapt-get file
$slapt_md5   = trim(`md5sum $slapt_path/slapt-get-$slapt_get_ver.tgz|sed 's| \/.*||g'`);

// mirror randomizer
$sites[0] = array("http://slackware.org.uk/gsb", 3);
$sites[1] = array("ftp://ftp.slackware.pl/pub/gnomeslackbuild/gsb", 3);
$sites[2] = array("http://slackware.rol.ru/gsb", 3);
$sites[3] = array("http://get.gnomeslackbuild.org/gsb", 1);
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

// main op
switch ($use_ver)
{
    case "$gsb_bin_stable_ver":
        $slapt_dir = "tools";
        $slack_mirror_uri =
            "http://slackware.mirrors.tds.net/pub/slackware/slackware-$slack_ver";
        break;
    case "current":
        $slapt_dir = "tools";
        $slack_mirror_uri =
            "http://slackware.mirrors.tds.net/pub/slackware/slackware-$slack_ver";
        break;
    default:
        $use_ver = $gsb_bin_stable_ver;
        $slack_mirror_uri =
            "http://slackware.mirrors.tds.net/pub/slackware/slackware-$slack_ver";
}

$output = "#!/usr/bin/env bash

#
#  +--------------------------------------------------------------------+
#  | GSB GNOME installer script                                         |
#  |   (c) 2008 Chip \"chipster\" Cuccio                                  |
#  |   <http://gnomeslackbuild.org/>                                    |
#  | This progam is distributed under the GNU GPL;                      |
#  |   See <http://www.gnu.org/licenses/gpl.html> for lic. info         |
#  +--------------------------------------------------------------------+
#

#
#  NOTE:
#  This script is dynamically generated on the web server.
#  It is not intended to be saved to a computer and invoked manually.
#  Doing so is unsupported, and the script may not function as intended.  
#

#
# basic paths
#
export PATH=\$PATH:/usr/sbin:/usr/local/bin

#
# vars
#
GSB_VER=\"$use_ver\"
META_PACK=\"gsb-complete\"
SLAPTGET_VER=\"$slapt_get_ver\"
SLAPTGET_FILE=\"slapt-get-\$SLAPTGET_VER.tgz\"
TMP=\"\${TMP:-/tmp}\"
MIRROR=\"$mirror\"
SLAPTGET_DLPATH=\"\$MIRROR/$arch_path\$GSB_VER/packages/$slapt_dir/\$SLAPTGET_FILE\"
TEMP_CONFIGFILE=\"\$TMP/slapt-getrc\"
SLAPTGET_ARGS0=\"--config \$TEMP_CONFIGFILE --retry 10 --upgrade -y\"
SLAPTGET_ARGS1=\"--config \$TEMP_CONFIGFILE --retry 10 --install \$META_PACK -y\"
WGET_ARGS=\"--progress=dot \$SLAPTGET_DLPATH -O \$TMP/\$SLAPTGET_FILE\"
SLAPT_MD5=$slapt_md5

#
# determine if r3wt
#
if [ `id -u` -ne 0 ]; then
    clear
    echo
    echo \"You must be root when running this program!\"
    echo \"log into a shell as root and run this command again;\"
    echo 
    echo \"     lynx --source http://gnomeslackbuild.org/net-install/$use_ver | sh\"
    echo
    exit 1
fi

#
# is wget installed?
#
WGET='/usr/bin/wget'
if [ ! -f \"\${WGET}\" ]; then
    clear
    echo 
    echo \"wget must be installed to run this program\"
    echo 
    exit 1
fi

#
# slapt-get handling/acquisition
#
SLAPTGET='/usr/sbin/slapt-get'
if [ ! -f \"\${SLAPTGET}\" ]; then
    clear
    echo 
    echo \"slapt-get not found. Downloading and installing...\"
    echo 
    \$WGET \$WGET_ARGS
    if [ ! -f \"\$TMP/\$SLAPTGET_FILE\" ]; then
        echo \"slapt-get download failed\"
        exit 1
    else
        TEMP_SLAPT_MD5=`md5sum \$TMP/\$SLAPTGET_FILE|sed 's| \/.*||g'|grep .`
        if [ \$TEMP_SLAPT_MD5 = \$SLAPT_MD5 ]; then
            upgradepkg --install-new  \$TMP/\$SLAPTGET_FILE
            rm -f \$TMP/\$SLAPTGET_FILE
        else
            echo \"slapt-get download md5's don't match\"
            exit 1
        fi
    fi
fi

#
# slapt-get preparedness
#
clear
echo
echo \"Preparing GSB GNOME installation...\"
echo
cat << EOF >\$TEMP_CONFIGFILE
WORKINGDIR=/var/slapt-get
EXCLUDE=^kernel-.*,^alsa-.*,^glibc.*,.*-[0-9]dl$,^devs$,^udev$,aaa_elflibs,x86_64
SOURCE=\$MIRROR/$arch_path\$GSB_VER
SOURCE=$slack_mirror_uri
EOF

#
# get package lists
#
echo
echo \"Updating package lists...\"
echo
\$SLAPTGET --config \$TEMP_CONFIGFILE --update
echo
echo \"Installing/updating GSB GNOME\"...
echo

#
# sanity
#
if \$SLAPTGET \$SLAPTGET_ARGS0; then
    #removepkg pkgconfig
    echo \"Dependencies upgraded - installing GSB GNOME...\"
    if \$SLAPTGET \$SLAPTGET_ARGS1; then
        \$SLAPTGET --config \$TEMP_CONFIGFILE --clean
        rm -f \$TEMP_CONFIGFILE
        echo \"GSB GNOME $use_ver has been installed!\"
        echo
    fi
else
    echo \"GSB GNOME installation failed!\"
    exit 1
fi
";

header("Content-type: text/plain\n");
print($output);

?>
