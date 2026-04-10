# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Development environment for **Spipu Bundles** — a suite of Symfony 5.4 bundles. The main application lives in `website/` and hosts 7 bundles as git submodules under `website/src/Spipu/`. An `App` demo application in `website/src/App/` exercises the bundles.

PHP 7.4 target (configured via `composer.json` platform).

## Commands

All quality scripts run from the repo root. Working directory for Composer/PHPUnit is `website/`.

```bash
# Install dependencies
cd website && composer install

# Add external Spipu bundle submodules
./architecture/add-bundles.sh

# Run PHPUnit (unit + functional tests, no coverage)
./quality/phpunit.sh

# Run PHPUnit with coverage (requires Xdebug)
./quality/phpunit.sh --coverage

# Run a specific test class or filter
./quality/phpunit.sh --filter "TestClassName"

# Full static analysis (PHPQA: phpcs, phpmd, pdepend, phpcpd, phpmetrics, phploc)
./quality/analyze.sh

# Architecture validation (Deptrac — requires graphviz for images)
./quality/deptrac.sh
```

PHPUnit uses `php7.4` explicitly. Tests bootstrap from `website/vendor/autoload.php` with config in `website/.phpunit.xml`. Test environment uses SQLite (`website/var-test/`); a temporary sodium keypair is generated per run.

## Architecture

### Bundle Dependency Hierarchy (enforced by Deptrac)

```
CoreBundle          ← no bundle dependencies
UiBundle            ← Core
UserBundle          ← Core, Ui
ConfigurationBundle ← Core, Ui
ProcessBundle       ← Core, Ui, Configuration
DashboardBundle     ← Core, Ui
ApiPartnerBundle    ← Core, Ui, Configuration
```

Deptrac configs: `.depfile.bundles.yaml` (inter-bundle), `.depfile.mvc.yaml` (MVC layers), `.depfile.global.yaml` (global rules).

### Autoloading (PSR-4)

- `App\` → `website/src/App/`
- `Spipu\` → `website/src/Spipu/`

Each bundle is a git submodule with its own `composer.json`. The root `composer.json` uses `replace` to avoid version conflicts.

### Test Structure

Tests live inside each bundle: `src/Spipu/<Bundle>/Tests/Unit/` and `src/Spipu/<Bundle>/Tests/Functional/`. Coverage only includes `src/Spipu/` (excluding Tests directories).

## Code Standards

- **PSR-12** (enforced by PHPCS via `website/.phpcs.xml`)
- `declare(strict_types=1)` in all PHP files
- Variable names: min 3 chars (except `$id`), max 30 chars (PHPMD via `website/.phpmd.xml`)
- No global functions (`Squiz.Functions.GlobalFunction`)
- No `sizeof()`/`count()` in loop conditions
- No TODO/FIXME comments (flagged by PHPCS)
- PHPMD rulesets: cleancode, codesize, design, naming, unusedcode, controversial

## Infrastructure

Container-based dev environments (Docker, LXC, LXD) are configured in `architecture/`. The `architecture/new-project.sh` script scaffolds new projects from this base.