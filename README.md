# Project spipu/symfony-dev

## Description

Dev environment for Spipu Bundles

## Install

Needed for Unit tests / Analyze the code without having to create LXC / LXD container

```bash
sudo apt-get -y install php7.2-cli php-xdebug php-common php-soap php-pdo php-sqlite3
```

Adding the external spipu bundles (we do not use composer because it is for dev only)

```bash
./architecture/add-bundles.sh
```

Initiate the env:

```bash
./architecture/full-init-xxx.sh
```

## Tests

to execute all the tests on the host machine:

```bash
./analyze.sh
```

to execute only the unit tests on the host machine:

```bash
./phpunit.sh
```

## License

This program is distributed under the MIT License. For more information see the [./LICENSE.md](./LICENSE.md) file.

Copyright 2019 Laurent Minguet

