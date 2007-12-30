<?php

$ident = '$Id: net-install.php 3276 2007-12-23 00:02:46Z chipster $';
// prevent confusion - wheee!
$ident = str_replace(".php", "", $ident);
require('common/versions_inc.php');

$slapt_md5   = trim(`md5sum $slapt_path/slapt-get-$slapt_get_ver.tgz|sed 's| \/.*||g'`);

switch ($use_ver) {
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
    case "unstable":
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
#  |   (c) 2005 Chip \"chipster\" Cuccio                                |
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
META_PACK=\"frgnome-libs frgnome-apps frgnome-gnome-apps frgnome-office frgnome-mono frgnome-applets_extensions frgnome-ruby frgnome planner goffice frgnome-bindings-c++ frgnome-bindings frgnome-bindings-perl frgnome-bindings-python\"
SLAPTGET_VER=\"$slapt_get_ver\"
SLAPTGET_FILE=\"slapt-get-\$SLAPTGET_VER.tgz\"
TMP=\"\${TMP:-/tmp}\"
MIRROR=\"http://slackware.org.uk/gsb\"
SLAPTGET_DLPATH=\"\$MIRROR/gsb-\$GSB_VER/packages/$slapt_dir/\$SLAPTGET_FILE\"
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
SOURCE=\$MIRROR/gsb-\$GSB_VER
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
echo \"Instaling/updating GSB GNOME\"...
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
