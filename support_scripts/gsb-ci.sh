#!/usr/bin/env bash

clear
sleep 1

echo ""
echo "Committing changes..."
echo ""
cd web
git commit -a
git push origin master
sleep 1

echo ""
echo "Getting git HEAD"
echo ""
git fetch

sleep 1

echo ""
echo "DONE!"
echo ""

