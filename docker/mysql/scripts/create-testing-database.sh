#!/usr/bin/env bash

# Temporary mysql config file to avoid the warning: "Using a password on the command line interface can be insecure."
# We can also dynamically use env variables which would not be understood in a .cnf file.

TEMPORARY_MYSQL_CONFIG=$(mktemp)

cat > "${TEMPORARY_MYSQL_CONFIG}" <<EOF
[client]
user=root
password=${MYSQL_ROOT_PASSWORD}
EOF

mysql --defaults-extra-file="${TEMPORARY_MYSQL_CONFIG}" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE_TESTING}\`;
    GRANT ALL PRIVILEGES ON \`${DB_DATABASE_TESTING}\`.* TO '${MYSQL_USER}'@'%';
EOSQL

rm -f "${TEMPORARY_MYSQL_CONFIG}"
