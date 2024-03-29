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
          "^aaa_elflibs,^devs,^glibc-.*,^kernel-.*,^udev,.*-[0-9]+dl$,x86_64,^compiz,^gst-plugins-.*";
        break;
    case "gsb64":
        $arch_path  = "gsb/gsb64";
        $slack_arch = "slackware64";
        $excludes   =
          "^aaa_elflibs,^devs,^glibc-.*,^kernel-.*,^udev,.*-[0-9]+dl$,i[3456]86,^compiz,^gst-plugins-.*";
        #$use_ver = "current"; // temp hack until stable is released.
        break;
    default:
        $arch       = "gsb";
        $arch_path  = "gsb/gsb";
        $slack_arch = "slackware";
        $excludes   =
          "^aaa_elflibs,^devs,^glibc-.*,^kernel-.*,^udev,.*-[0-9]+dl$,x86_64,^compiz,^gst-plugins-.*";
}

// get the req. URI for help
$request_uri = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

// need version vars
require('versions_inc.php');

// mirror vars
$slack_mirror_uri =
        "http://ftp.osuosl.org/pub/slackware/$slack_arch-$slack_ver";

// slapt-get md5 vars
$slapt_md5   = trim(`md5sum $slapt_path/slapt-get-$slapt_get_ver.txz|sed 's| \/.*||g'`);

// GSB mirror randomizer
$mirror = "http://mirrors.gnomeslackbuild.org";

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
SLAPTGET_ARGS0=\"--config \$TEMP_CONFIGFILE --retry 10 --remove-obsolete --upgrade -y\"
SLAPTGET_ARGS1=\"--config \$TEMP_CONFIGFILE --retry 10 --remove-obsolete --install \$META_PACK -y\"
WGET_ARGS=\"--progress=bar \$SLAPTGET_DLPATH -O \$TMP/\$SLAPTGET_FILE -U GSB_net-installer\"
SLAPT_MD5=$slapt_md5
LOGFILE=\"/tmp/gsb-installation_\$( date +%Y%m%d-%H%M%S ).log\"
SLAPTGET='/usr/sbin/slapt-get'

#
# determine if user is logged in as root user
#
if [ `id -u` -ne 0 ]; then
    clear
    echo
    echo \"You must be root when running this program!\"
    echo \"log into a shell as root and run this command again;\"
    echo 
    echo \"    lynx --source $request_uri | bash\"
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
echo \"Downloading and installing slapt-get...\"
echo 
sleep 2
\$WGET \$WGET_ARGS | tee -a \$LOGFILE
if [ ! -f \"\$TMP/\$SLAPTGET_FILE\" ]; then
    echo \"slapt-get download failed\"
    exit 1
else
    TEMP_SLAPT_MD5=`md5sum \$TMP/\$SLAPTGET_FILE|sed 's| \/.*||g'|grep .`
    if [ \$TEMP_SLAPT_MD5 = \$SLAPT_MD5 ]; then
       upgradepkg --install-new --reinstall \$TMP/\$SLAPTGET_FILE | tee -a \$LOGFILE
       rm -f \$TMP/\$SLAPTGET_FILE
       echo \"slapt-get installed successfully\"
       echo
       sleep 3
    else
       echo
       echo \"slapt-get download md5's don't match! Exiting.\"
       echo
       exit 1
    fi
fi

#
# slapt-get preparedness
#
echo
echo \"Preparing GSB GNOME installation...\"
echo
sleep 3
cat << EOF >\$TEMP_CONFIGFILE
WORKINGDIR=/var/slapt-get
EXCLUDE=$excludes
SOURCE=\$MIRROR/\$GSB_NORMALIZED_PATH
SOURCE=$slack_mirror_uri
EOF

#
# get package lists & add keys
#
echo
echo \"Grabbing and importing package signing keys....\"
echo
\$SLAPTGET --config \$TEMP_CONFIGFILE --add-keys | tee -a \$LOGFILE
echo
echo \"Updating package lists...\"
echo
\$SLAPTGET --config \$TEMP_CONFIGFILE --update | tee -a \$LOGFILE
echo
echo \"Installing/updating GSB GNOME\"...
echo
sleep 3

#
# sanity
#
if \$SLAPTGET \$SLAPTGET_ARGS0 | tee -a \$LOGFILE; then
    echo \"Dependencies upgraded and/or installed - installing GSB GNOME...\"
    if \$SLAPTGET \$SLAPTGET_ARGS1 | tee -a \$LOGFILE; then
        echo
        echo \"cleaning up temporary files...\"
        \$SLAPTGET --config \$TEMP_CONFIGFILE --clean
        rm -f \$TEMP_CONFIGFILE
        echo \"Done\"
        echo
        # the following logic re-installs GSB packages which may have been
        # overwitten by the slackware patches.  important we patch too! 
        SLAPTRC='/etc/slapt-get/slapt-getrc'
        if [ \"$( grep gsb \"\${SLAPTRC}\" )\" ]; then
            sleep 1
        else
            echo
            echo \"GSB has been installed - however...\"
            echo
            echo \"We've detected that you do not have slapt-get\"
            echo \"configured to use the GSB mirrors! This could happen\"
            echo \"if you already had slapt-get installed.\"
            echo
            echo \"This means your GSB software may have packages that are\"
            echo \"out of date.  To rectify this, you must add the GSB\"
            echo \"mirror(s) to your slapt-getrc as described on our\"
            echo \"website, and then run this command again to update GSB.\"
            echo
            echo \"Steps:\"
            echo
            echo \"  1) follow the instructions at\"
            echo \"     <http://gnomeslackbuild.org/download/#slaptgetrc>\"
            echo \"  2) run the following command again, as root:\"
            echo \"     lynx --source $request_uri | bash\"
            echo
        fi
        echo
        echo \"GSB GNOME $use_ver has been installed!\"
        echo
        echo \"To start GNOME or GDM, please see: \"
        echo \"<http://gnomeslackbuild.org/support/#FAQs>\"
        echo
        echo \"We hope you enjoy using GSB! :^)\"
        echo
        echo \"        -- The GSB Development Team\"
        echo
    fi
else
    echo
    echo \"GSB GNOME installation failed! Exiting\"
    echo
    exit 1
fi
";

header("Content-type: text/plain\n");
print($output);

?>
