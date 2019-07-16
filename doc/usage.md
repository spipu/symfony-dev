# Usage

## Init a new project

Copy the `architecture` folder.

Modify the project name (it must contain only chars, no minus or underscore) in the `architecture/conf/env.sh` file.

Create the symfony project with the following script:

```bash
./architecture/new-project.sh
```

## Dev Environment
 
### LXC

```bash
# Create the dev env
./architecture/create-lxc.sh

# Remove the dev env
./architecture/remove-lxc.sh
```

### LXD

```bash
# Create the dev env
./architecture/create-lxd.sh

# Remove the dev env
./architecture/remove-lxd.sh
```

### Docker

```bash
# Create the dev env
./architecture/create-docker.sh

# Start the dev env
./architecture/start-docker.sh

# Remove the dev env
./architecture/remove-docker.sh
```

If needed, you can create the file `architecture/conf/env.local.sh` and put the following content (for MacOS for example):

```bash
#!/bin/bash

# Docker Parameters
ENV_DOCKER_IP="127.0.0.1"
ENV_DOCKER_PORT_START="20000"
```
