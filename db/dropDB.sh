#!/bin/bash

. ./commonDB.sh

#
#	create the database & the database user
#
mysql --host=localhost --user=root <<EOF
DROP DATABASE $DBNAME;
EXIT
EOF
