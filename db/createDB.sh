#!/bin/bash

. ./commonDB.sh

#
#	create the database & the database user
#
mysql --host=localhost --user=root <<EOF
CREATE DATABASE IF NOT EXISTS $DBNAME;
USE mysql;
DELETE FROM user WHERE User="$DBUSER";
FLUSH PRIVILEGES;
GRANT ALL PRIVILEGES ON *.* TO "$DBUSER"@"localhost" IDENTIFIED BY "$DBPASS" WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO "$DBUSER"@"%" IDENTIFIED BY "$DBPASS" WITH GRANT OPTION;
EXIT
EOF

#
#	create the tables
#
mysql --host=localhost --user=$DBUSER --password=$DBPASS --database=$DBNAME <<EOF
CREATE TABLE IF NOT EXISTS Settings (
	Name varchar(100) NOT NULL default "",
	Value varchar(1000) NOT NULL default "",
	PRIMARY KEY (Name)
) COMMENT='Settings';

CREATE TABLE IF NOT EXISTS Users (
	Uid int(5) unsigned NOT NULL auto_increment,
	Username varchar(50) NOT NULL default "",
	Password varchar(50) NOT NULL default "",
	Admin int(1) NOT NULL default 0,
	PRIMARY KEY (Uid),
	UNIQUE KEY (Username)
) COMMENT='Users';

EOF

#
#	process initial sql script
#
for SQL in init/*.sql; do
	if [ -f $SQL ]; then
		echo "Processing initial sql script: $SQL"
		mysql --host=localhost --user=$DBUSER --password=$DBPASS --database=$DBNAME <$SQL
	fi
done
