#!/bin/bash

# Option --no-browser: do not open the reports in Firefox
NO_BROWSER=0
ARGS=()
for ARG in "$@"; do
  if [[ "$ARG" == "--no-browser" ]]; then
    NO_BROWSER=1
  else
    ARGS+=("$ARG")
  fi
done
set -- "${ARGS[@]}"

# needed for output: apt-get install graphviz

# Go into the project folder
cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../website/

# Create the build folder
LOG_FOLDER="../quality/build/"
mkdir -p $LOG_FOLDER

./vendor/bin/deptrac analyse --config-file=./.depfile.global.yaml  --no-cache
./vendor/bin/deptrac analyse --config-file=./.depfile.mvc.yaml     --no-cache
./vendor/bin/deptrac analyse --config-file=./.depfile.bundles.yaml --no-cache

./vendor/bin/deptrac analyse --config-file=./.depfile.global.yaml  --no-cache --formatter=graphviz-image --output="${LOG_FOLDER}deptrac-global.png"
./vendor/bin/deptrac analyse --config-file=./.depfile.mvc.yaml     --no-cache --formatter=graphviz-image --output="${LOG_FOLDER}deptrac-mvc.png"
./vendor/bin/deptrac analyse --config-file=./.depfile.bundles.yaml --no-cache --formatter=graphviz-image --output="${LOG_FOLDER}deptrac-bundles.png"

# Output
if [[ "$NO_BROWSER" == "1" ]]; then
  echo "Report: ${LOG_FOLDER}deptrac-global.png"
  echo "Report: ${LOG_FOLDER}deptrac-mvc.png"
  echo "Report: ${LOG_FOLDER}deptrac-bundles.png"
else
  firefox "${LOG_FOLDER}deptrac-global.png"
  firefox "${LOG_FOLDER}deptrac-mvc.png"
  firefox "${LOG_FOLDER}deptrac-bundles.png"
fi
