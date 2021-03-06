#!/bin/bash

. ./commonDB.sh

DBTABLES="$( echo "SHOW TABLES;" | mysql --host=localhost --user=$DBUSER --password=$DBPASS --database=$DBNAME --skip-column-names )"

DATE=$( date +%Y%m%d-%H%M%S )

mkdir -p dump/$DATE


#
#	complete dump
#
mysqldump --host=localhost --user=$DBUSER --password=$DBPASS $DBNAME \
	--opt \
	--skip-extended-insert \
	>dump/$DATE/dump-$DBNAME.sql
ln -sf $DATE/dump-$DBNAME.sql dump/dump-$DBNAME.sql

#
#	dump table wise
#
for DBTABLE in $DBTABLES; do
	mysqldump --host=localhost --user=$DBUSER --password=$DBPASS $DBNAME $DBTABLE \
		--opt \
		--skip-extended-insert \
		>dump/$DATE/dump-$DBNAME-$DBTABLE.sql
	ln -sf $DATE/dump-$DBNAME-$DBTABLE.sql dump/dump-$DBNAME-$DBTABLE.sql
done
