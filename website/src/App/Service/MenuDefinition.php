<?php

/**
 * This file is a demo file for Spipu Bundles
 *
 * (c) Laurent Minguet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Service;

use Spipu\ConfigurationBundle\Service\ConfigurationManager;
use Spipu\UiBundle\Entity\Menu\Item;
use Spipu\UiBundle\Service\Menu\DefinitionInterface;

class MenuDefinition implements DefinitionInterface
{
    private ?Item $mainItem = null;
    private ConfigurationManager $configurationManager;

    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    private function build(): void
    {
        $this->mainItem = new Item($this->configurationManager->get('app.website.name'), '', 'app_home');

        $this->mainItem
            ->setIcon('code', 'dark', 'Dev environment')
            ->addChild('spipu.ui.page.home', 'home', 'app_home')
                ->getParentItem()
            ->addChild('spipu.dashboard.page.home.title', 'spipu-dashboard', 'app_dashboard')
                ->getParentItem()
            ->addChild('app.page.test', 'test', 'app_test')
                ->setACL(true)
                ->getParentItem()

            ->addChild('spipu.api_partner.label.api_partner')
                ->addChild('spipu.api_partner.label.api_swagger', 'api_partner_swagger', 'app_api_partner_swagger')
                    ->setACL(true, 'ROLE_ADMIN_API_PARTNER')
                    ->getParentItem()
                ->addChild('spipu.api_partner.label.api_logs', 'api_partner_log', 'app_api_partner_log_list')
                    ->setACL(true, 'ROLE_ADMIN_API_PARTNER')
                    ->getParentItem()
                ->getParentItem()

            ->addChild('spipu.ui.page.admin')
                ->addChild(
                    'spipu.configuration.page.admin.list',
                    'spipu-configuration-admin',
                    'spipu_configuration_admin_list'
                )
                    ->setACL(true, 'ROLE_ADMIN_MANAGE_CONFIGURATION_SHOW')
                    ->getParentItem()
                ->addChild(
                    'spipu.process.page.admin.task.list',
                    'spipu-process-admin-task',
                    'spipu_process_admin_task_list'
                )
                    ->setACL(true, 'ROLE_ADMIN_MANAGE_PROCESS_SHOW')
                    ->getParentItem()
                ->addChild(
                    'spipu.process.page.admin.log.list',
                    'spipu-process-admin-log',
                    'spipu_process_admin_log_list'
                )
                    ->setACL(true, 'ROLE_ADMIN_MANAGE_PROCESS_SHOW')
                    ->getParentItem()
                ->addChild('spipu.user.page.admin.list', 'spipu-user-admin', 'spipu_user_admin_list')
                    ->setAcl(true, 'ROLE_ADMIN_MANAGE_USER_SHOW')
                    ->getParentItem()
                ->getParentItem()
            ->addChild('spipu.user.page.profile.show', 'spipu-user-profile', 'spipu_user_profile_show')
                ->setACL(true)
                ->setIcon('user')
                ->getParentItem()
            ->addChild('spipu.user.page.security.log_in', 'spipu-user-login', 'spipu_user_security_login')
                ->setACL(false)
                ->setIcon('sign-in-alt')
                ->getParentItem()
            ->addChild('spipu.user.page.security.log_out', 'spipu-user-logout', 'spipu_user_security_logout')
                ->setACL(true)
                ->setIcon('sign-out-alt')
                ->getParentItem()
        ;
    }

    public function getDefinition(): Item
    {
        if (!$this->mainItem) {
            $this->build();
        }

        return $this->mainItem;
    }
}
