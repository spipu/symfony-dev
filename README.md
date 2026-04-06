# Project spipu/symfony-dev

## Description

Dev environment for Spipu Bundles

* [Usage](/doc/usage.md)

**Bundles Description**

* [Bundle ApiPartner](/doc/bundle-api-partner.md)
* [Bundle Configuration](/doc/bundle-configuration.md)
* [Bundle Core](/doc/bundle-core.md)
* [Bundle Dashboard](/doc/bundle-dashboard.md)
* [Bundle Process](/doc/bundle-process.md)
* [Bundle Ui](/doc/bundle-ui.md)
* [Bundle User](/doc/bundle-user.md)

![Bundle UI Grid](/doc/ui/ui-grid.png)

## Requirements

It works with:

* PHP >= 8.3
* Composer
* Symfony = 7.4.*
* Doctrine ORM = 3.*.*

## Others

Needed for Unit tests / Analyze the code without having to create LXC / LXD container

```bash
sudo apt-get -y install php8.3-cli php8.3-xdebug php8.3-common php8.3-soap php8.3-pdo php8.3-sqlite3 php8.3-xml php8.3-mbstring php8.3-zip
```

Adding the external spipu bundles (we do not use composer because it is for dev only)

```bash
./architecture/add-bundles.sh
```

## Tests

to execute all the tests on the host machine:

```bash
./quality/analyze.sh
```

to execute only the unit tests on the host machine:

```bash
./quality/phpunit.sh
```

## Demo

Look at https://github.com/spipu/symfony-demo for a full demo

## License

This program is distributed under the MIT License. For more information see the [./LICENSE.md](./LICENSE.md) file.

Copyright 2026 Laurent Minguet

