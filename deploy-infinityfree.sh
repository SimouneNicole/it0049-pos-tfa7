#!/usr/bin/env bash
# ====================================================================
# IT0049 POS TFA7 - Automated FTP Deployment to InfinityFree
# Simoune Nicole - it0049-tfa7.freehosting.dev
# Loads FTP credentials from environment variables or gitignored .deploy.env
# ====================================================================

set -e

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$DIR"

if [ -f "$DIR/.deploy.env" ]; then
    set -a
    # shellcheck disable=SC1091
    source "$DIR/.deploy.env"
    set +a
fi

python3 "$DIR/scripts/deploy_infinityfree.py"
