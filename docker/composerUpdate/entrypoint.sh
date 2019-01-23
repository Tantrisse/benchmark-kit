#!/usr/bin/env bash

source /var/phpbenchmarks/componentFiles/.phpbenchmarks/configuration.sh
source /var/phpbenchmarks/common.sh

function generateComposerFiles {
    local phpVersion=$1
    local enabled=$2
    local composerLockFile="composer.lock.php$phpVersion"

    echoValidationGroupStart "Composer update with PHP $phpVersion"

    if [ -f "$composerLockFile" ]; then
        rm $composerLockFile
    fi

    if [ "$enabled" == "true" ]; then
        composer update
        mv composer.lock $composerLockFile
        echoValidationGroupEnd
    else
        echoValidationGroupEnd 0 "(disabled by configuration)"
    fi
}

definePhpComponentConfigurationValues

validateComposerJson

cd /var/www/phpbenchmarks
generateComposerFiles "5.6" "$PHPBENCHMARKS_PHP_5_6_ENABLED"
generateComposerFiles "7.0" "$PHPBENCHMARKS_PHP_7_0_ENABLED"
generateComposerFiles "7.1" "$PHPBENCHMARKS_PHP_7_1_ENABLED"
generateComposerFiles "7.2" "$PHPBENCHMARKS_PHP_7_2_ENABLED"
generateComposerFiles "7.3" "$PHPBENCHMARKS_PHP_7_3_ENABLED"

echo ""
validateComposerLock
