# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is `spipu/symfony-dev`, a Symfony 7.4 microkernel application serving as the development and testing environment for a suite of reusable **Spipu Bundles**. The bundles are not installed via Composer — they live directly under `website/src/Spipu/` and are cloned from GitHub via `./architecture/add-bundles.sh`.

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
php8.3 ./bin/phpunit -c ./.phpunit.xml --no-coverage --filter TestClassName
```

> The test runner requires `APP_ENCRYPTOR_KEY_PAIR` env var (sodium keypair). The `phpunit.sh` script sets this automatically. If running phpunit manually, set it: `export APP_ENCRYPTOR_KEY_PAIR=$(php -r "echo sodium_bin2base64(sodium_crypto_box_keypair(), SODIUM_BASE64_VARIANT_ORIGINAL);")`

**Update Composer dependencies (on the dev server):**
```bash
# From website/ directory:
composer update
```

**System requirements for running tests locally (no container):**
```bash
sudo apt-get -y install php8.3-cli php-xdebug php-common php-soap php-pdo php-sqlite3 php-xml
```

## Dev Environment

The application runs on a LXC container, not locally. Access:
- **App user:** `ssh delivery@symfonydev.lxc` — run Symfony/Composer commands here
- **Root (provisioning):** `ssh root@symfonydev.lxc`

Composer and Symfony CLI commands must be executed on the container (from `/var/www/symfonydev/website/`).

### Provisioning

The `architecture/scripts/provision/` scripts run in numeric order during container creation (`01-repo`, `02-upgrade`, `03-packages`, `10-php`, `11-apache`, etc.). PHP is installed via the PPA `ondrej/php` (configured in `10-php.sh`).

## Architecture

### Directory Layout

```
website/          # Symfony 7.4 application
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
| **CoreBundle** | Foundation: base `AbstractBundle`, `AbstractController` (shared `trans()`/`addFlashTrans()`), shared services, utilities |
| **UiBundle** | Grid/list/form UI framework with Twig extensions |
| **ConfigurationBundle** | Key-value application configuration with UI management |
| **UserBundle** | User management, authentication, security policies, roles/permissions (depends on ConfigurationBundle) |
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

### UserBundle Security System

UserBundle uses ConfigurationBundle for runtime security settings stored in database (`config/spipu_configuration.yaml`):
- `user.security.lock_enabled` / `lock_max_attempts` — automatic account lockout after N failed login attempts (disables account via `UserManager::disableUser()`)
- `user.security.token_expiration` — activation/recovery token lifetime in hours
- `user.security.password_min_length` — minimum password length (enforced minimum: 8)

Security hardening:
- Password recovery is blocked for disabled accounts (Doctrine query filters on `active = true`)
- Login error messages are normalized (generic translated message via `security` domain, prevents account enumeration)
- `UserLoginSubscriber::onLoginFailed()` returns early if the account is already disabled (prevents redundant `disableUser()` calls and event spam)
- Rate limiting via Symfony's `login_throttling` (5 attempts / 15 minutes, configured in app's `security.yaml`, requires `symfony/rate-limiter`)

Key services:
- **`UserManager`** — central service for `enableUser()`, `disableUser()`, `validatePassword()`, `changeEmail()`. All enable/disable operations must go through this service (not `setActive()` directly).
- **`UserConfiguration`** — typed getters for security settings from ConfigurationBundle
- **`UserLoginSubscriber`** — listens to Symfony login events, increments `nbTryLogin` on failure (only if account is active), disables account when threshold reached

Events dispatched: `spipu.user.action.{enable,disable,email_change}` via `UserEvent`, `spipu.user.password.validate` via `PasswordValidationEvent` (extensible for custom password rules).

CLI commands: `spipu:user:enable <username>`, `spipu:user:disable <username>`.

### App-Level Extension Points

The `website/src/App/` directory is where application-specific implementations live:
- `App\Step\` — custom process steps (tagged `spipu.process.step`)
- `App\WidgetSource\` — custom dashboard widgets (tagged `spipu.widget.source`, implement `SourceDefinitionInterface` + `SourceDataDefinitionInterface`)
- `App\Api\Route\` — custom API endpoints (auto-registered)
- `App\DependencyInjection\AppExtension.php` — app-level role hierarchy via `RoleDefinitionInterface`
- `App\Kernel.php` — registers `AppExtension` manually in `build()` (Symfony 7.4 no longer auto-detects it)

### Role Hierarchy

Each bundle can contribute RBAC roles by returning a `RoleDefinitionInterface` from `AbstractBundle::getRolesHierarchy()`. The app itself does the same via `AppExtension` (registered in `Kernel::build()`). Roles from all bundles are merged at boot time via `SpipuCoreBundle::prependExtension()`.

### Service Wiring

`website/config/services.yaml` wires the application:
- Dashboard widgets tagged as `spipu.widget.source`
- Process steps tagged as `spipu.process.step`
- API routes registered via `App\Api\Route\`
- Sessions backed by Redis via Predis

### Testing Layout

PHPUnit discovers tests via glob patterns in `website/.phpunit.xml`:
- Unit tests: `src/Spipu/*/tests/Unit`
- Functional tests: `src/Spipu/*/tests/Functional`

Tests run with `APP_ENV=test` and SQLite (no database server needed).

All bundle controllers must extend `Spipu\CoreBundle\Controller\AbstractController` (not Symfony's directly). This base class provides `trans()`, `addFlashTrans()` with optional `$domain` parameter, and subscribes to the `translator` service. Exception: `DashboardControllerService` which injects `TranslatorInterface` directly.

CoreBundle provides `SymfonyMock` test helpers (`getContainerBuilder()`, `getContainerConfigurator()`) for testing bundle configuration loading. ProcessBundle provides `SpipuProcessMock` for process-related test setup. ConfigurationBundle provides `SpipuConfigurationMock::getManager()` for mocking `ConfigurationManager`. UserBundle provides `SpipuUserMock` for user entity and token manager mocks, and `UserManagerTest::getService()` as a factory for creating `UserManager` instances in tests.

## Coding Standards

### PHP Version and Strict Types

- Target: **PHP 8.3**, **Symfony 7.4**, **Doctrine ORM 3** — do not use syntax or features from later versions.
- Code must remain compatible with PHP 8.3 through 8.5. Avoid patterns deprecated or removed in later versions:
  - Always use `?Type $param = null` (never implicit nullable `Type $param = null`).
  - Always use `{$var}` for string interpolation (never `${var}`).
  - Never use `get_class()` or `get_parent_class()` without arguments — use `$object::class` or `static::class`.
  - Never use `utf8_encode()` / `utf8_decode()` — use `mb_convert_encoding()`.
  - Never use partial callable strings (`"self::method"`, `["parent", "method"]`) — use `Closure::fromCallable()` or first-class callable syntax.
- Do not add encoding conversion or error suppression on `mb_*` functions: warnings on invalid UTF-8 are intentional (input data must be UTF-8).
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
- Setter methods accepting a `Closure` or `callable` must have a phpdoc comment documenting the expected signature: `Format: function(Type $param, ...): ReturnType`.

### Controller Conventions

- Never use Symfony's automatic entity parameter binding (ParamConverter/MapEntity). Always inject the repository and `int $id`, load manually, and throw `createNotFoundException()` if not found.
- Use `#[IsGranted('ROLE_...')]` attribute for access control (not `@Security` annotations).
- Use `$request->query->get()` for GET parameters, `$request->request->get()` for POST parameters, `$request->query->all()` for array GET parameters. Never use `$request->get()` (deprecated in Symfony 7.4).

### Console Command Conventions

- All commands must use the `#[AsCommand(name: '...', description: '...')]` attribute. Do not use `setName()` or `setDescription()` in `configure()`.

### PSR-12 and Formatting

- Code must be PSR-12 compliant.
- No superfluous blank lines: no blank line before a closing `}`, no double blank lines, no trailing blank line after removing code.
- Function signatures exceeding 120 characters must be split across multiple lines.
- Every PHP and YAML file must end with exactly one blank line.

### Database Schema

- Do **not** use Symfony migrations. Use `doctrine:schema:update` to synchronize the database schema with entity definitions.