#!/usr/bin/env bash

# syncs staging site to prod.

rsync -trP --delete --exclude=.svn /home/chipster/webs/stage.gnomeslackbuild.org/web \
    /home/chipster/webs/gnomeslackbuild.org/
