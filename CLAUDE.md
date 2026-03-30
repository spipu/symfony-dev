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

**StepInterface** requires only one method:
```php
public function execute(ParametersInterface $parameters, LoggerInterface $logger): mixed;
```

Steps are fetched from the DI container **by FQCN** (not instantiated directly). Any class tagged `spipu.process.step` is resolvable at runtime via `$container->get('Fully\\Qualified\\ClassName')`. When adding a new step, it must be tagged in services.yaml.

**Parameter interpolation** uses a custom `{{ variable_name }}` syntax (not Twig). Full replacement (`"{{ key }}"`) returns the actual typed value; partial (`"prefix_{{ key }}"`) concatenates strings. Parameters resolve up a parent chain, so nested steps (e.g. inside LoopStep) can access outer parameters.

**LoopStep** (`ProcessBundle/src/Step/LoopStep.php`) dynamically builds and runs child steps defined inline in YAML. Inside the loop body, these variables are available:
- `loop.key` — current iteration key
- `loop.value` — current iteration value
- `loop.result.<step_code>` — return value from a previous step in this iteration

**Minimal YAML process structure:**
```yaml
spipu_process:
    my_process:
        name: "Human-readable name"
        options:
            can_be_put_in_queue: true
            can_be_rerun_automatically: false
        steps:
            step_one:
                class: App\Step\MyStep
                parameters:
                    some_param: "value"
```

### App-Level Extension Points

The `website/src/App/` directory is where application-specific implementations live:
- `App\Step\` — custom process steps (tagged `spipu.process.step`)
- `App\WidgetSource\` — custom dashboard widgets (tagged `spipu.dashboard.source`, implement `SourceDefinitionInterface` + `SourceDataDefinitionInterface`)
- `App\Api\Route\` — custom API endpoints (auto-registered)
- `App\DependencyInjection\AppExtension.php` — app-level role hierarchy via `RoleDefinitionInterface`

### Role Hierarchy

Each bundle can contribute RBAC roles by returning a `RoleDefinitionInterface` from `AbstractBundle::getRolesHierarchy()`. The app itself does the same via `AppExtension`. Roles from all bundles are merged at boot time.

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

Tests run with `APP_ENV=test` and SQLite (no database server needed).

CoreBundle provides `SymfonyMock` test helpers (`getContainerBuilder()`, `getContainerConfigurator()`) for testing bundle configuration loading. ProcessBundle provides `SpipuProcessMock` for process-related test setup.

## Coding Standards

### PHP Version and Strict Types

- Target: **PHP 8.1** — do not use syntax or features from later versions.
- Every PHP file must have `declare(strict_types=1);` preceded by a blank line after `<?php`.
- All constants must have explicit visibility (`public`, `protected`, or `private`).

### Typing Rules

- All parameters, return types, and properties must be typed when the type is reliably determinable.
- Only allowed union: `?type` (nullable). No `A|B` unions unless imposed by an external contract (e.g. PSR-3 `string|Stringable`).
- If a type is genuinely non-expressible with heterogeneous types, use `mixed`.
- `callable`: allowed as native type on **method parameters**; forbidden on **properties** (use `@var callable|null` phpdoc instead).
- `resource`: no native type possible — keep phpdoc `@param resource` / `@return resource`.
- For `__construct`, `__destruct`, `__clone`: no return type.
- Fluent methods return `self`.
- Closures and callbacks should be typed based on the contract they fulfill.

### PSR-12 and Formatting

- Code must be PSR-12 compliant.
- Function signatures exceeding 120 characters must be split across multiple lines.
- Every PHP and YAML file must end with exactly one blank line.