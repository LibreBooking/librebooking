#!/bin/bash
# Sets up a fresh LibreBooking database with optional sample data.
# Adjust DB_NAME and mysql credentials as needed.
set -eu

SCHEMA_DIR="$(cd "$(dirname "$0")" && pwd)"
DB_NAME="${DB_NAME:-librebooking}"

# Create a fresh database
read -rp "Proceed and erase any existing database called ${DB_NAME} [y/N] " answer
if [[ "${answer}" =~ ^[Yy] ]]; then
    echo "Erasing any existing database called ${DB_NAME}"
    mysql -u root -e "DROP DATABASE IF EXISTS ${DB_NAME}; CREATE DATABASE ${DB_NAME};"
else
    echo "Not proceeding"
    exit 1
fi

# Create base schema
echo "Create the base schema"
mysql -u root "${DB_NAME}" --abort-source-on-error \
    -e "SOURCE ${SCHEMA_DIR}/create-schema.sql"

# Apply all upgrade scripts in version order
while IFS= read -r -d '' v; do
    for f in schema.sql data.sql; do
        if [ -f "${SCHEMA_DIR}/upgrades/${v}/${f}" ]; then
            echo "Applying ${SCHEMA_DIR}/upgrades/${v}/${f}"
            mysql -u root "${DB_NAME}" --abort-source-on-error \
                -e "SOURCE ${SCHEMA_DIR}/upgrades/${v}/${f}"
        fi
    done
done < <(find "${SCHEMA_DIR}/upgrades/" -mindepth 1 -maxdepth 1 -type d -printf '%f\0' | sort -zV)

# Load initial data
echo "Loading initial data"
mysql -u root "${DB_NAME}" --abort-source-on-error \
    -e "SOURCE ${SCHEMA_DIR}/create-data.sql"

# Ask about sample data
read -rp "Load sample data? (2 users, 2 resources) [y/N] " answer
if [[ "${answer}" =~ ^[Yy] ]]; then
    echo "Loading sample data"
    mysql -u root "${DB_NAME}" --abort-source-on-error \
        -e "SOURCE ${SCHEMA_DIR}/sample-data-utf8.sql"

    read -rp "Also load large sample data? (20 users, 150 resources) [y/N] " answer2
    if [[ "${answer2}" =~ ^[Yy] ]]; then
        echo "Loading large sample data"
        mysql -u root "${DB_NAME}" --abort-source-on-error \
            -e "SOURCE ${SCHEMA_DIR}/sample-data-large-utf8.sql"
    fi
fi

echo "Database setup complete."
