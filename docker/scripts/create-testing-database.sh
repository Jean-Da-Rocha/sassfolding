#!/usr/bin/env bash

# Convert uppercase characters to lowercase and suffix by _testing.
DB_DATABASE_TESTING=$(echo "${APP_NAME}" | tr '[:upper:]' '[:lower:]')_testing

mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS ${DB_DATABASE_TESTING};
    GRANT ALL PRIVILEGES ON \`${DB_DATABASE_TESTING}\`.* TO '${MYSQL_USER}'@'%';
EOSQL
