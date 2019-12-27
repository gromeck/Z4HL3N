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
	Name VARCHAR(100) NOT NULL DEFAULT "",
	Value VARCHAR(1000) NOT NULL DEFAULT "",
	PRIMARY KEY (Name)
) COMMENT="Settings";

CREATE TABLE IF NOT EXISTS Users (
	Uid INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	Username VARCHAR(50) NOT NULL DEFAULT "",
	Password VARCHAR(50) NOT NULL DEFAULT "",
	Admin INT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (Uid),
	UNIQUE KEY (Username)
) COMMENT="Users";

CREATE TABLE IF NOT EXISTS Scores (
	Sid INT(5) UNSIGNED NOT NULL auto_increment,
	Name VARCHAR(50) NOT NULL default "",
	Numbers INT(4) NOT NULL default 0,
	Time INT(4) NOT NULL default 0,
	Timestamp DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
	PRIMARY KEY (Sid)
) COMMENT="Scores";

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
