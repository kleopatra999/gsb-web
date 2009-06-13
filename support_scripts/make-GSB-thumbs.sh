#!/usr/bin/env bash

#$Id: make-GSB-thumbs.sh,v 1.3 2007/09/23 21:42:59 chipster Exp $

MAIN_PATH="../"
HTML_FILE="$MAIN_PATH/common/screenshots_inc.html"
#MIRROR_PATH="http://mirror.datapipe.net/norlug/frg-screenies"
HTML_IMAGE_PATH="/screenies"
PHYS_IMAGE_PATH="$MAIN_PATH/screenies"

clear


echo "resizing screenshots ..."
echo ""

cd $PHYS_IMAGE_PATH
mogrify -verbose -geometry 800x600 *.png

echo ""
echo "removing existing thumbs ..."
echo ""

cd $PHYS_IMAGE_PATH/thumbs
for i in $( ls thumb-* ); do
    rm -f $i
    echo "      deleted '$i'"
done

echo ""
echo "generating thumbnails from regular images (this may take a minute) ..."
echo ""

cd ../
echo "" > $HTML_FILE
for i in $( ls *.png ); do
    PAGE_ENTRY="    <a href=\"$HTML_IMAGE_PATH/$i\" rel=\"lightbox[Screenshots]\"><img src=
      \"$HTML_IMAGE_PATH/thumbs/thumb-$i\" alt=\"\" class=\"screenthumb\" /></a>"
    echo "      Generating 'thumb-$i' and adding to HTML page..."
    convert -geometry 150x150 $i thumbs/thumb-$i
    echo "$PAGE_ENTRY" >> $HTML_FILE
done

echo ""
echo "DONE!"
echo ""
echo ""

exit
