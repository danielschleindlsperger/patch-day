#!/bin/bash

set -x

# Don't need it anymore
rm deploy-key.enc
chmod 600 deploy-key
mv deploy-key ~/.ssh/id_rsa

rsync --version

rsync -r --delete-after --quiet --exclude-from=rsync_exclude.txt $TRAVIS_BUILD_DIR deploy@45.63.116.5:/var/www/patch-day

exit 0