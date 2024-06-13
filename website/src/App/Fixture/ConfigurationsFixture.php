<?php

declare(strict_types=1);

namespace App\Fixture;

use Spipu\ConfigurationBundle\Exception\ConfigurationException;
use Spipu\ConfigurationBundle\Service\ConfigurationManager;
use Spipu\CoreBundle\Fixture\FixtureInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurationsFixture implements FixtureInterface
{
    /**
     * @var ConfigurationManager
     */
    private ConfigurationManager $manager;

    /**
     * @var bool
     */
    private bool $isDisabled = false;

    /**
     * @param ConfigurationManager $manager
     */
    public function __construct(ConfigurationManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return void
     */
    public function disable(): void
    {
        $this->isDisabled = true;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return 'sample-configuration';
    }

    /**
     * @param OutputInterface $output
     * @return void
     * @throws ConfigurationException
     */
    public function load(OutputInterface $output): void
    {
        if ($this->isDisabled) {
            return;
        }

        $output->writeln("Update Sample Configuration");

        $configuration = $this->getConfiguration();

        foreach ($configuration as $key => $value) {
            if ($this->manager->getDefinition($key)->getType() === 'encrypted') {
                $this->manager->setEncrypted($key, $value);
                continue;
            }
            $this->manager->set($key, $value);
        }
    }

    /**
     * @param OutputInterface $output
     * @return void
     */
    public function remove(OutputInterface $output): void
    {
        $output->writeln("Remove Configuration is disabled");
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }

    /**
     * @return array
     */
    private function getConfiguration(): array
    {
        return [
            'app.email.sender'             => 'no-reply@symfonydev.lxc',
            'app.website.url'              => 'https://symfonydev.lxc',
            'process.folder.export'        => '/var/www/symfonydev/website/var/export/',
            'process.folder.import'        => '/var/www/symfonydev/website/var/import/',
            'process.task.can_kill'        => 1,
            'api.partner.enabled'          => 1,
            'api.partner.api_key'          => "b45dbcb30ceb5e7b82ab589334833762472e5ff53ba68cbfc67cd0047934fc77",
            'api.partner.api_secret'       => "c3c4980cee649badabc215b43a1e880b55136591b0fa0b6454b1b112be1ba456",
        ];
    }
}
