#!/bin/bash

showMessage " > Yarn - Install"

corepack enable --install-directory /usr/local/bin
corepack prepare yarn@1.22.22 --activate

showMessage " > Yarn - Version"

yarn --version
