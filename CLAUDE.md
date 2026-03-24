# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is `spipu/symfony-dev`, a Symfony 6.4 microkernel application serving as the development and testing environment for a suite of reusable **Spipu Bundles**. The bundles are not installed via Composer — they live directly under `website/src/Spipu/` and are cloned from GitHub via `./architecture/add-bundles.sh`.

## Common Commands

All commands run from the repo root unless noted.

**Add/update Spipu bundles from GitHub:**
```bash
./architecture/add-bundles.sh
```

**Run all unit tests:**
```bash
./quality/phpunit.sh
```

**Run unit tests with coverage (opens Firefox):**
```bash
./quality/phpunit.sh --coverage
```

**Run full code quality analysis (phpmetrics, phpcs, phpmd, etc.):**
```bash
./quality/analyze.sh
```

**Run a specific test or filter:**
```bash
# From website/ directory:
php8.1 ./bin/phpunit -c ./.phpunit.xml --no-coverage --filter TestClassName
```

> The test runner requires `APP_ENCRYPTOR_KEY_PAIR` env var (sodium keypair). The `phpunit.sh` script sets this automatically. If running phpunit manually, set it: `export APP_ENCRYPTOR_KEY_PAIR=$(php -r "echo sodium_bin2base64(sodium_crypto_box_keypair(), SODIUM_BASE64_VARIANT_ORIGINAL);")`

**System requirements for running tests locally (no container):**
```bash
sudo apt-get -y install php8.1-cli php-xdebug php-common php-soap php-pdo php-sqlite3
```

## Architecture

### Directory Layout

```
website/          # Symfony 6.4 application
  src/
    App/          # Application-specific code (controllers, entities, services)
    Spipu/        # The Spipu bundles (local source, not Composer-installed)
  config/
    process/      # YAML process definitions for ProcessBundle workflows
  tests/          # PHPUnit bootstrap
quality/          # Test and analysis scripts
architecture/     # Scripts for LXC/LXD/Docker environment setup
doc/              # Bundle documentation
```

### The Spipu Bundles

Seven bundles under `website/src/Spipu/`, each following a consistent internal structure (`src/`, `tests/Unit/`, `tests/Functional/`):

| Bundle | Purpose |
|--------|---------|
| **CoreBundle** | Foundation: base `AbstractBundle`, shared services, utilities |
| **UiBundle** | Grid/list/form UI framework with Twig extensions |
| **ConfigurationBundle** | Key-value application configuration with UI management |
| **UserBundle** | User management, authentication, roles/permissions |
| **ProcessBundle** | Background job system: process definitions (YAML), step execution, logging |
| **DashboardBundle** | Widget-based dashboard system |
| **ApiPartnerBundle** | REST API framework for partner integrations (scoped tokens, OAuth-style) |

### ProcessBundle Step System

Processes are defined in `website/config/process/` as YAML files. Each process is a sequence of **Steps** — PHP classes implementing `StepInterface`. Custom steps for the app live in `website/src/App/Step/`. Built-in steps are in `ProcessBundle/src/Step/` (Generic/, File/, plus `LoopStep.php` for iteration).

### Service Wiring

`website/config/services.yaml` wires the application:
- Dashboard widgets tagged as `spipu.dashboard.source`
- Process steps tagged as `spipu.process.step`
- API routes registered via `App\Api\Route\`
- Sessions backed by Redis via Predis

### Testing Layout

PHPUnit discovers tests via glob patterns in `website/.phpunit.xml`:
- Unit tests: `src/Spipu/*/tests/Unit`
- Functional tests: `src/Spipu/*/tests/Functional`

Tests run with `APP_ENV=dev` and SQLite (no database server needed).