<?php declare(strict_types=1);

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
        $event->getGridDefinition()->addColumn(
            (new Grid\Column('middle_name', 'spipu.user.field.middle_name', 'middleName', 35))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT)))
                    ->useSortable()
        );
    }
}
