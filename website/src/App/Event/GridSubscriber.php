<?php
declare(strict_types=1);

namespace App\Event;

use Spipu\UiBundle\Entity\Grid;
use Spipu\UiBundle\Event\GridDefinitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Form Listener
 */
class GridSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            GridDefinitionEvent::PREFIX_NAME . 'user' => 'onGrid',
        ];
    }

    /**
     * @param GridDefinitionEvent $event
     * @return void
     * @throws \Spipu\UiBundle\Exception\GridException
     */
    public function onGrid(GridDefinitionEvent $event): void
    {
        $grid = $event->getGridDefinition();

        $grid
            ->addColumn(
                (new Grid\Column('middle_name', 'spipu.user.field.middle_name', 'middleName', 35))
                        ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT)))
                        ->useSortable()
            )
            ->addGlobalAction(
                (new Grid\Action('create', 'spipu.ui.action.create', 10, 'spipu_user_admin_create'))
                    ->setIcon('edit')
                    ->setNeededRole('ROLE_ADMIN_MANAGE_USER_EDIT')
                    ->setCssClass('success')
            )
        ;
    }
}
