#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../../

ENV_DO_NOT_GENERATE="yes"
source ./architecture/scripts/include/init.sh

showTitle "Provision"

export DEBIAN_FRONTEND=noninteractive
export LC_ALL=C

source ./architecture/scripts/provision/env-${ENV_CODE}.sh

export DEBIAN_FRONTEND=dialog
