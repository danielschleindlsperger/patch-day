#!/bin/bash

# Import the SSH deployment key
openssl aes-256-cbc -K $encrypted_22009518e18d_key -iv $encrypted_22009518e18d_iv -in deploy-key.enc -out deploy-key -d

# Don't need it anymore
rm deploy-key.enc
chmod 600 deploy-key
mv deploy-key ~/.ssh/id_rsa

$ERRORSTRING="Error. Please make sure you've indicated correct parameters"
if [ $# -eq 0 ]
    then
        echo $ERRORSTRING;
elif [ $1 == "live" ]
    then
        if [[ -z $2 ]]
            then
                echo "Running dry-run"
                rsync --dry-run -az --force --delete --progress
                --exclude-from=rsync_exclude.txt -e "ssh -p22" ./
                deploy@45.63.116.5:/var/www/patch-day
        elif [ $2 == "go" ]
            then
                echo "Running actual deploy"
                rsync -az --force --delete --progress
                --exclude-from=rsync_exclude.txt -e "ssh -p22" ./ deploy@45.63
                .116.5:/var/www/website-name
        else
            echo $ERRORSTRING;
        fi
fi