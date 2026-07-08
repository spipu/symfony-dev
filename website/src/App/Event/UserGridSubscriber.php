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

namespace App\Event;

use Spipu\UiBundle\Entity\Grid;
use Spipu\UiBundle\Event\GridDefinitionEvent;
use Spipu\UserBundle\Entity\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Example of how to extend a Spipu grid from a consumer project: the user management row/mass
 * actions (edit, enable, disable) are added here on purpose, instead of being shipped by the
 * UserBundle itself, so they can serve as a reference without being enabled by default.
 */
class UserGridSubscriber implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GridDefinitionEvent::PREFIX_NAME . 'user' => 'onGrid',
        ];
    }

    public function onGrid(GridDefinitionEvent $event): void
    {
        $currentUserId = $this->getCurrentUser()->getId();

        $grid = $event->getGridDefinition();

        $grid
            ->setPersonalize(true)
            ->addColumn(
                (new Grid\Column('middle_name', 'spipu.user.field.middle_name', 'middleName', 35))
                        ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT)))
                        ->useSortable()
                        ->setFilter(
                            (new Grid\ColumnFilter(true, false))
                                ->setValueTransformer(fn(string $v): string => mb_strtolower($v))
                        )
                        ->setDisplayed(false)
            )
            ->addRowAction(
                (new Grid\Action('edit', 'spipu.ui.action.edit', 20, 'spipu_user_admin_edit'))
                    ->setCssClass('success')
                    ->setIcon('pen-to-square')
                    ->setNeededRole('ROLE_ADMIN_MANAGE_USER_EDIT')
            )
            ->addRowAction(
                (new Grid\Action('enable', 'spipu.user.action.enable', 30, 'app_user_grid_enable'))
                    ->setCssClass('info')
                    ->setIcon('check')
                    ->setNeededRole('ROLE_ADMIN_MANAGE_USER_EDIT')
                    ->setConditions(['id' => ['neq' => $currentUserId], 'active' => ['neq' => 1]])
            )
            ->addRowAction(
                (new Grid\Action('disable', 'spipu.user.action.disable', 40, 'app_user_grid_disable'))
                    ->setCssClass('warning')
                    ->setIcon('xmark')
                    ->setNeededRole('ROLE_ADMIN_MANAGE_USER_EDIT')
                    ->setConditions(['id' => ['neq' => $currentUserId], 'active' => ['eq' => 1]])
            )
            ->addMassAction(
                (new Grid\Action('enable', 'spipu.user.action.enable', 50, 'app_user_grid_mass_enable'))
                    ->setCssClass('info')
                    ->setIcon('check')
                    ->setNeededRole('ROLE_ADMIN_MANAGE_USER_EDIT')
            )
            ->addMassAction(
                (new Grid\Action('disable', 'spipu.user.action.disable', 60, 'app_user_grid_mass_disable'))
                    ->setCssClass('warning')
                    ->setIcon('xmark')
                    ->setNeededRole('ROLE_ADMIN_MANAGE_USER_EDIT')
            )
        ;
    }

    private function getCurrentUser(): UserInterface
    {
        /** @var UserInterface $user */
        $user = $this->tokenStorage->getToken()->getUser();

        return $user;
    }
}
