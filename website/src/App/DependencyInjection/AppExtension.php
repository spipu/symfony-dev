<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Service\RoleDefinition;
use Spipu\CoreBundle\DependencyInjection\RolesHierarchyExtensionExtensionInterface;
use Spipu\CoreBundle\Service\RoleDefinitionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class AppExtension extends Extension implements RolesHierarchyExtensionExtensionInterface
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @return void
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
    }

    public function getRolesHierarchy(): RoleDefinitionInterface
    {
        return new RoleDefinition();
    }
}
