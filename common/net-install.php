<?php

// op
$use_ver = $_REQUEST['use_ver'];
// arch
$arch = $_REQUEST['arch'];
switch ($arch)
{
    case "gsb":
        $arch_path  = "gsb/gsb";
        $slack_arch = "slackware";
        $excludes   =
          "^kernel-.*,^glibc.*,.*-[0-9]dl$,^devs$,^udev$,aaa_elflibs,x86_64";
        break;
    case "gsb64":
        $arch_path  = "gsb/gsb64";
        $slack_arch = "slackware64";
        $excludes   =
          "^kernel-.*,^glibc.*,.*-[0-9]dl$,^devs$,^udev$,aaa_elflibs";
        #$use_ver = "current"; // temp hack until stable is released.
        break;
    default:
        $arch       = "gsb";
        $arch_path  = "gsb/gsb";
        $slack_arch = "slackware";
        $excludes   =
          "^kernel-.*,^glibc.*,.*-[0-9]dl$,^devs$,^udev$,aaa_elflibs,x86_64";
}

// need version vars
require('versions_inc.php');

// mirror vars
$slack_mirror_uri =
        "http://slackware.mirrors.tds.net/pub/slackware/$slack_arch-$slack_ver";

// slapt-get md5 vars
$slapt_md5   = trim(`md5sum $slapt_path/slapt-get-$slapt_get_ver.txz|sed 's| \/.*||g'`);

// mirror randomizer
$sites[0] = array("http://slackware.org.uk", 0);
$sites[1] = array("ftp://ftp.slackware.pl/pub/gnomeslackbuild", 3);
$sites[2] = array("http://slackware.rol.ru/gsb", 3);
$sites[3] = array("http://mirrors.dotsrc.org", 3);
$sites[4] = array("http://get.gnomeslackbuild.org", 1);
$sites[5] = array("http://mirror.switch.ch/ftp/mirror", 3);
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
    case "$gsb_bin_stable":
        $slapt_dir = "ad";
        break;
    case "current":
        $slapt_dir = "ad";
        break;
    default:
        $use_ver = $gsb_bin_stable;
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
SLACK_VER=\"$slack_ver\"
GSB_VER=\"$use_ver\"
GSB_NORMALIZED_PATH=\"$arch_path-\$GSB_VER\"_\"$slack_arch-\$SLACK_VER\"
META_PACK=\"gsb-desktop\"
SLAPTGET_VER=\"$slapt_get_ver\"
SLAPTGET_FILE=\"slapt-get-\$SLAPTGET_VER.txz\"
TMP=\"\${TMP:-/tmp}\"
MIRROR=\"$mirror\"
SLAPTGET_DLPATH=\"\$MIRROR/\$GSB_NORMALIZED_PATH/$arch/$slapt_dir/\$SLAPTGET_FILE\"
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
    echo \"lynx --source http://gnomeslackbuild.org/net-install | sh\"
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
EXCLUDE=$excludes
SOURCE=\$MIRROR/\$GSB_NORMALIZED_PATH
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
