#!/usr/bin/env bash

source common.sh
source validation/configurationValidation.sh

echoValidationGroupStart "Validation of .phpbenchmarks directory"
copyConfigurationFiles "$CONFIGURATION_PATH"
assertCommonConfiguration
echoValidationGroupEnd

validateBranchName

cp common.sh docker/composerUpdate
copyConfigurationFiles "docker/composerUpdate/componentFiles/.phpbenchmarks"

source docker/composerUpdate/createEnv.sh
echoValidationGroupStart "Building Docker container"
cd docker/composerUpdate
if [ $VERBOSE_LEVEL -ge 2 ]; then
    docker-compose up --build --no-start
    [ $? != "0" ] && exitScript "Error while building Docker container."
else
    docker-compose up --build --no-start &>/tmp/phpbenchmarks.docker
    [ $? != "0" ] \
        && cat /tmp/phpbenchmarks.docker \
        && rm /tmp/phpbenchmarks.docker \
        && exitScript "Error while building Docker container."
fi
echoValidationGroupEnd

echoValidationGroupStart "Composer update"
if [ $VERBOSE_LEVEL -ge 1 ]; then
    docker-compose up --abort-on-container-exit --exit-code-from phpbenchmarks_composer_update
    [ $? != "0" ] && exitScript "Composer update failed."
else
    docker-compose up --abort-on-container-exit --exit-code-from phpbenchmarks_composer_update &>/tmp/phpbenchmarks.docker
    [ $? != "0" ] \
        && cat /tmp/phpbenchmarks.docker \
        && rm /tmp/phpbenchmarks.docker \
        && exitScript "Composer update failed."
fi
echoValidationGroupEnd

echoValidationOk "Composer update done. To validate your code, use \"./codeValidation.sh $COMPONENT_TYPE $RESULT_TYPE_SLUG $INSTALLATION_PATH\"."
