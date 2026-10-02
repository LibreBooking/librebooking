#!/usr/bin/env bash
#
# (Re)create the integration test database: schema, upgrades, base data and
# sample data. Used by the Integration Tests workflow and for local runs.
#
# DESTRUCTIVE: drops and recreates the database named by LB_TEST_DB_NAME. To
# protect real data, the name must end in "_test".
#
# Environment variables:
#   LB_TEST_DB_HOST      Database host (default: 127.0.0.1)
#   LB_TEST_DB_PORT      Database port (default: 3306)
#   LB_TEST_DB_NAME      Database name, must end in "_test" (default: librebooking_test)
#   LB_TEST_DB_USER      Database user (required)
#   LB_TEST_DB_PASSWORD  Database password (default: empty)
#
# The user needs privileges to drop and create LB_TEST_DB_NAME and full access
# to it. Example local setup (as a MariaDB/MySQL admin):
#   CREATE USER 'lbtest'@'localhost' IDENTIFIED BY 'lbtest';
#   CREATE USER 'lbtest'@'127.0.0.1' IDENTIFIED BY 'lbtest';
#   GRANT ALL ON librebooking_test.* TO 'lbtest'@'localhost', 'lbtest'@'127.0.0.1';
#
# Usage:
#   LB_TEST_DB_USER=lbtest LB_TEST_DB_PASSWORD=lbtest tests/Integration/setup-test-database.sh

set -euo pipefail

db_host="${LB_TEST_DB_HOST:-127.0.0.1}"
db_port="${LB_TEST_DB_PORT:-3306}"
db_name="${LB_TEST_DB_NAME:-librebooking_test}"
db_user="${LB_TEST_DB_USER:?LB_TEST_DB_USER must be set}"
db_password="${LB_TEST_DB_PASSWORD:-}"

if [[ "${db_name}" != *_test ]]; then
    echo "ERROR: refusing to recreate database '${db_name}': the name must end in '_test'." >&2
    exit 1
fi

if [[ ! "${db_name}" =~ ^[A-Za-z0-9_]+$ ]]; then
    echo "ERROR: database name '${db_name}' may only contain letters, digits and underscores." >&2
    exit 1
fi

if [[ "${db_port}" != "3306" ]]; then
    # phing-tasks/UpgradeDbTask.php only accepts a host, so it always uses the default port.
    echo "ERROR: LB_TEST_DB_PORT must be 3306; the upgrade task cannot use another port." >&2
    exit 1
fi

if command -v mariadb >/dev/null 2>&1; then
    mysql_client=mariadb
elif command -v mysql >/dev/null 2>&1; then
    mysql_client=mysql
else
    echo "ERROR: neither the 'mariadb' nor the 'mysql' client was found." >&2
    exit 1
fi

root_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
schema_dir="${root_dir}/database_schema"

# Pass the password via the environment so it doesn't appear in the process list.
export MYSQL_PWD="${db_password}"
db() {
    "${mysql_client}" --host="${db_host}" --port="${db_port}" --user="${db_user}" "$@"
}

echo "Recreating database ${db_name} on ${db_host}:${db_port}"
db --execute="DROP DATABASE IF EXISTS \`${db_name}\`; CREATE DATABASE \`${db_name}\`;"

echo "Loading schema"
db "${db_name}" <"${schema_dir}/create-schema.sql"

echo "Applying upgrades"
upgrade_output="$(php "${root_dir}/phing-tasks/UpgradeDbTask.php" "${db_user}" "${db_password}" "${db_host}" "${db_name}" "${schema_dir}")"
echo "${upgrade_output}"
# A failing statement normally makes mysqli throw, so the task exits non-zero and
# "set -e" stops here. If mysqli error reporting is turned off, the task instead
# prints "Failed on statement" and exits 0, so check its output too.
if grep -q '^Failed on statement' <<<"${upgrade_output}"; then
    echo "ERROR: a database upgrade statement failed." >&2
    exit 1
fi

echo "Loading base data"
db "${db_name}" <"${schema_dir}/create-data.sql"

echo "Loading sample data"
db "${db_name}" <"${schema_dir}/sample-data-utf8.sql"

echo "Test database ${db_name} is ready."
