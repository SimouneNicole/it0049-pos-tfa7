#!/usr/bin/env bash
# ====================================================================
# IT0049 POS TFA6 - Automated FTP Deployment to InfinityFree
# Simoune Nicole - it0049-tfa6.infinityfreeapp.com
# ====================================================================

set -e

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$DIR"

python3 "$DIR/scripts/deploy_infinityfree.py"
