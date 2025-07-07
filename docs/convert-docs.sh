#!/bin/bash

set -u
# set -e

export PS4='+ ${BASH_SOURCE:-}:${FUNCNAME[0]:-}:L${LINENO:-}:   '
# set -x

TOP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && git rev-parse --show-toplevel)"

echo "${TOP_DIR}"

for filepath in "${TOP_DIR}"/doc/*.md
do
    echo "${filepath}"
    filename=$(basename -- "${filepath}")
    extension="${filename##*.}"
    filename="${filename%.*}"
    if [[ "${filename}" == "README" ]]; then
        filename="DEVELOPER-README"
    fi
    pandoc "${filepath}" -f markdown -t rst -o "${TOP_DIR}/docs/source/${filename}.rst"
done
