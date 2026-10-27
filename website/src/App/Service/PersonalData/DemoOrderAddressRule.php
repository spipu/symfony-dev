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

namespace App\Service\PersonalData;

use App\Entity\DemoOrderAddress;
use Spipu\UiBundle\Entity\EntityInterface;

/**
 * The addresses are created with their order: same creation date, same state as the order.
 */
class DemoOrderAddressRule extends AbstractDemoRule
{
    public function getEntityClass(): string
    {
        return DemoOrderAddress::class;
    }

    public function getFields(): array
    {
        return ['firstname', 'lastname', 'phone', 'email', 'address'];
    }

    public function isVisible(EntityInterface $entity): bool
    {
        /** @var DemoOrderAddress $entity */
        return $this->isWithinLimit($entity->getCreatedAt());
    }

    public function getVisibleDqlCondition(string $alias): array
    {
        return $this->getCreatedAtVisibleDqlCondition($alias);
    }
}
