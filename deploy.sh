#!/bin/bash

openssl aes-256-cbc -K $encrypted_05c77b57f590_key -iv $encrypted_05c77b57f590_iv -in deploy-key.enc -out /tmp/deploy_rsa -d
eval "$(ssh-agent -s)"
rm deploy-key.enc
chmod 600 /tmp/deploy_rsa
ssh-add /tmp/deploy_rsa

# dry run
rsync -avzu --dry-run --exclude-from="./.rsync-exclude.txt" --rsh="ssh -i /tmp/deploy_rsa" ./ deploy@45.63.116.5:/var/www/patch-day

# for real now
rsync -avzu --exclude-from="./.rsync-exclude.txt" --rsh="ssh -i /tmp/deploy_rsa" ./ deploy@45.63.116.5:/var/www/patch-day

# some optimization and migrations
ssh -i /tmp/deploy_rsa deploy@45.63.116.5 'cd /var/www/patch-day &&  php artisan cache:clear && php artisan route:clear && php artisan route:cache && php artisan view:clear && php artisan migrate'

exit 0