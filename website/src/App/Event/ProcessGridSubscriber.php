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

use Spipu\UiBundle\Event\GridDefinitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Form Listener
 */
class ProcessGridSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            GridDefinitionEvent::PREFIX_NAME . 'process_task' => 'onGrid',
        ];
    }

    /**
     * @param GridDefinitionEvent $event
     * @return void
     */
    public function onGrid(GridDefinitionEvent $event): void
    {
        $grid = $event->getGridDefinition();

        $column = $grid->getColumn('status');
        $column->addOption('filter-css-class', 'foo-bar');
        $column->getFilter()->useMultipleValues(true);
    }
}
