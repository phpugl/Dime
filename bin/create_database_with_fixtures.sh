#!/bin/sh

SCRIPT=$(readlink -f "$0")
SCRIPTPATH=`dirname "$SCRIPT"`

php $SCRIPTPATH/../app/console doctrine:database:drop --force

php $SCRIPTPATH/../app/console doctrine:database:create

php $SCRIPTPATH/../app/console doctrine:schema:update --force

php $SCRIPTPATH/../app/console doctrine:fixtures:load