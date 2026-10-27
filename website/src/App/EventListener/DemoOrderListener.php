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

namespace App\EventListener;

use App\Entity\DemoOrder;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: DemoOrder::class)]
class DemoOrderListener
{
    public function prePersist(DemoOrder $order): void
    {
        $order->getCustomer()?->setLastOrderedAt($order->getCreatedAt() ?? new DateTime());
    }
}
