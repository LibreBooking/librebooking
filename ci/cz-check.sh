#!/bin/bash

set -e
set -u
set -o pipefail
trap 'echo "Exit status $? at line $LINENO from: $BASH_COMMAND"' ERR

export PS4='+ ${BASH_SOURCE:-}:${FUNCNAME[0]:-}:L${LINENO:-}:   '
# set -x

# Ensure that our starting commit is really an ancestor of HEAD
git merge-base --is-ancestor 8a420dd06cb2b07748953255420556b0ded7d769 HEAD || exit 1
if ! cz check --rev-range 8a420dd06cb2b07748953255420556b0ded7d769..HEAD; then
    echo ""
    echo ""
    echo "ERROR: The 'cz' check failed. "
    echo "Commit messages must start with a valid commit 'type'"
    echo "Valid commit 'types' are: build:, ci:, docs:, feat:, fix:, perf:, refactor:, style:, test:, chore:, revert:, bump:"
    echo "A 'scope' can be provided in parenthesis if desired. Like: 'docs(API): Improve the API documentation'"
    echo ""
    echo "For example a commit message could be something like:"
    echo "  ci(cz-lint): Give a more helpful message when 'cz' fails"
    echo ""
    echo "For more info, see: https://www.conventionalcommits.org/"
    echo ""
    echo ""
    exit 1
fi
