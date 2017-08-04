#!/bin/bash


# Don't need it anymore
chmod 600 deploy-key

# dry run
rsync -avzu --dry-run --exclude-from=".rsync-exclude.txt" --rsh="ssh -i /tmp/deploy_rsa" $TRAVIS_BUILD_DIR/ deploy@45.63.116.5:/var/www/patch-day

# for real now
rsync -avzu --exclude-from=".rsync-exclude.txt" --rsh="ssh -i /tmp/deploy_rsa" $TRAVIS_BUILD_DIR/ deploy@45.63.116.5:/var/www/patch-day

# some optimization and migrations
ssh -i /tmp/deploy_rsa deploy@45.63.116.5 'cd /var/www/patch-day &&  php artisan cache:clear && php artisan route:clear && php artisan route:cache && php artisan view:clear && php artisan optimize && php artisan migrate'

exit 0