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

use App\Entity\DemoOrder;
use Spipu\UiBundle\Entity\EntityInterface;

class DemoOrderRule extends AbstractDemoRule
{
    public function getEntityClass(): string
    {
        return DemoOrder::class;
    }

    public function getFields(): array
    {
        return ['customerFirstName', 'customerLastName', 'customerEmail'];
    }

    public function isVisible(EntityInterface $entity): bool
    {
        /** @var DemoOrder $entity */
        return $this->isWithinLimit($entity->getCreatedAt());
    }

    public function getVisibleDqlCondition(string $alias): array
    {
        return $this->getCreatedAtVisibleDqlCondition($alias);
    }
}
