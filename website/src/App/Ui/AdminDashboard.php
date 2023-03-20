<?php

declare(strict_types=1);

namespace App\Ui;

use Spipu\DashboardBundle\Entity\Dashboard as Dashboard;
use Spipu\DashboardBundle\Service\Ui\Definition\DashboardDefinitionInterface;

class AdminDashboard implements DashboardDefinitionInterface
{
    /**
     * @var Dashboard\Dashboard|null
     */
    private ?Dashboard\Dashboard $definition = null;

    /**
     * @return Dashboard\Dashboard
     */
    public function getDefinition(): Dashboard\Dashboard
    {
        if ($this->definition === null) {
            $this->definition = $this->prepareDefinition();
        }

        return $this->definition;
    }

    /**
     * @return Dashboard\Dashboard
     */
    private function prepareDefinition(): Dashboard\Dashboard
    {
        return (new Dashboard\Dashboard('admin'))
            ->setTemplateShowMain('dashboard/dashboard-show.html.twig')
            ->setTemplateConfigureMain('dashboard/dashboard-configure.html.twig')
        ;
    }

    /**
     * @return array
     */
    public function getDefaultConfig(): array
    {
        return [];
    }
}
