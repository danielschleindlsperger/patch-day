#!/bin/bash

# Don't need it anymore
rm deploy-key.enc
chmod 600 deploy-key
mv deploy-key ~/.ssh/id_rsa

rsync -az --force --delete --progress --exclude-from=rsync_exclude.txt -e
"ssh -p22" ./ deploy@45.63.116.5:/var/www/patch-day