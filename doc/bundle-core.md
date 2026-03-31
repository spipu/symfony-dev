# Bundle - Core

## Description

The **CoreBundle** is the foundation of the Spipu bundle suite. It provides the shared infrastructure that all other bundles depend on:

- **AbstractBundle** — base class for all Spipu bundles (auto-loads `services.yaml`, exposes role hierarchy)
- **Encryptor** — asymmetric encryption via PHP Sodium (libsodium)
- **AsynchronousCommand** — run Symfony console commands in background processes
- **MailManager** — send HTML/text emails via Twig templates or raw HTML
- **RoleDefinitionInterface** — contract for contributing RBAC roles to the hierarchy
- **HasherFactory** — factory for creating a `NativePasswordHasher`
- **FixtureInterface / ListFixture** — database fixture loading system
- **Assets / AssetInterface** — bundle asset publishing (vendor, URL, or ZIP sources)
- **Slugger** — URL-safe slug generation (ASCII, lowercase)
- **Filesystem / FinderFactory** — testable wrappers around Symfony Filesystem and Finder
- **Environment** — application environment descriptor (`dev` / `preprod` / `prod`)

Full documentation: [README.md](https://github.com/spipu/symfony-bundle-core/blob/master/README.md)
