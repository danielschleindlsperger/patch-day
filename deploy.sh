#!/bin/bash

# Don't need it anymore
rm deploy-key.enc
chmod 600 deploy-key
mv deploy-key ~/.ssh/id_rsa

rsync --version

rsync -az --force --delete-after --progress --exclude-from=rsync_exclude.txt -e
"ssh -p22" $TRAVIS_BUILD_DIR deploy@45.63.116.5:/var/www/patch-day