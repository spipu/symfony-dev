<?php

declare(strict_types=1);

namespace App\Service;

use Spipu\CoreBundle\Entity\Role\Item;
use Spipu\CoreBundle\Service\RoleDefinitionInterface;

class RoleDefinition implements RoleDefinitionInterface
{
    /**
     * @return void
     */
    public function buildDefinition(): void
    {
        Item::load('ROLE_ADMIN_PROCESS_SLEEP')
            ->setLabel('app.role.process_sleep')
            ->setWeight(1)
            ->addChild('ROLE_ADMIN')
        ;

        Item::load('ROLE_ADMIN_PROCESS_BIG_NAME')
            ->setLabel('app.role.process_big_name')
            ->setWeight(1)
            ->addChild('ROLE_ADMIN')
        ;

        Item::load('ROLE_ADMIN_AVAILABLE_PROCESSES')
            ->setLabel('app.role.available_processes')
            ->setWeight(99)
            ->addChild('ROLE_ADMIN_PROCESS_SLEEP')
            ->addChild('ROLE_ADMIN_PROCESS_BIG_NAME')
        ;

        Item::load('ROLE_ADMIN_MANAGE_PROCESS')
            ->addChild('ROLE_ADMIN_AVAILABLE_PROCESSES')
        ;
    }
}
