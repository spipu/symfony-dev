<?php

declare(strict_types=1);

namespace App\Fixture;

use Spipu\ConfigurationBundle\Service\ConfigurationManager;
use Spipu\CoreBundle\Fixture\FixtureInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurationsFixture implements FixtureInterface
{
    private ConfigurationManager $manager;
    private bool $isDisabled = false;

    public function __construct(ConfigurationManager $manager)
    {
        $this->manager = $manager;
    }

    public function disable(): void
    {
        $this->isDisabled = true;
    }

    public function getCode(): string
    {
        return 'sample-configuration';
    }

    public function load(OutputInterface $output): void
    {
        if ($this->isDisabled) {
            return;
        }

        $output->writeln("Update Sample Configuration");

        $configuration = $this->getConfiguration();

        foreach ($configuration as $key => $value) {
            $this->manager->set($key, $value);
        }
    }

    public function remove(OutputInterface $output): void
    {
        $output->writeln("Remove Configuration is disabled");
    }

    public function getOrder(): int
    {
        return 1;
    }

    private function getConfiguration(): array
    {
        return [
            'app.email.sender'             => 'no-reply@symfonydev.lxc',
            'app.website.url'              => 'https://symfonydev.lxc',
            'process.task.can_kill'        => 1,
        ];
    }
}
