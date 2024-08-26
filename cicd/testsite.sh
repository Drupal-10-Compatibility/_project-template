#!/usr/bin/env bash

# Tests the site after installing and starting the PHP server
# To make sure it returns a 200 response code.

# Site requires an input variable, which is the connection string
# used to establish the database connection for conducting a site
# intallation.

# For Github Actions, this is passed in from the job, and is set in
# a variable in the calling action.

set -e

SIMPLETEST_DB=$1

## Installs site from existing configuration.
./vendor/bin/drush @self -yes si --existing-config --db-url=$SIMPLETEST_DB
## If doing a fresh install without existing config
# ./vendor/bin/drush drush site-install standard --yes --account-pass=admin --db-url=$SIMPLETEST_DB
## Start php server with the default drupal router, and capture job ID.
nohup php -S 127.0.0.1:8888 ./cicd/.htrouter.php &> ./tmp/server.log &
PHP_SERVER_PID=$!
## Sleep for 5 seconds, giving the site an opportunity to get started.
sleep 5
## Execute a curl command that parses the server response, capturing only the
## http response code.
OUTPUT=$(curl -s -o /dev/null -I -w "%{http_code}" 127.0.0.1:8888)
## Checks the response code, outputs a success or failure message, kills the
## PHP server and exits the script, returning 0 exit code if server return
## a 200, or a 1 exit code if the server returns a different response code.
if [[ "$OUTPUT" == 200 ]]; then
  echo "Test Successful, site loaded!";
  kill -9 $PHP_SERVER_PID;
  exit 0;
else
  echo "Test Failed";
  kill -9 $PHP_SERVER_PID;
  exit 1;
fi
