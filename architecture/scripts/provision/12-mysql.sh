#!/bin/bash

if [[ "$ENV_ARCHITECTURE" != "arm" ]]; then
  source ./architecture/scripts/provision/12-mysql-percona.sh
else
  source ./architecture/scripts/provision/12-mysql-oracle.sh
fi
