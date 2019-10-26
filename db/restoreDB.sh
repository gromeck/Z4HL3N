#!/bin/bash

. ./commonDB.sh

DUMP="dump/$1/dump-$DBNAME.sql"

if [ -f $DUMP ]; then
	echo "Restoring $DUMP ..."
	mysql --host=localhost --user=$DBUSER --password=$DBPASS $DBNAME <$DUMP
else
	echo "Dumpfile $DUMP not found"
fi
