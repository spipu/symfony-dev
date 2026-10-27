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

use App\Entity\DemoCustomer;
use Spipu\UiBundle\Entity\EntityInterface;

/**
 * A customer is hidden when MAX(createdAt, lastOrderedAt) is older than the limit.
 * An order is always created after its customer, so MAX(createdAt, lastOrderedAt) = lastOrderedAt ?? createdAt.
 */
class DemoCustomerRule extends AbstractDemoRule
{
    public function getEntityClass(): string
    {
        return DemoCustomer::class;
    }

    public function getFields(): array
    {
        return ['firstname', 'lastname', 'email', 'phone', 'birthday'];
    }

    public function isVisible(EntityInterface $entity): bool
    {
        /** @var DemoCustomer $entity */
        return $this->isWithinLimit($entity->getLastOrderedAt() ?? $entity->getCreatedAt());
    }

    public function getVisibleDqlCondition(string $alias): array
    {
        // Indexable form of "COALESCE(lastOrderedAt, createdAt) >= :limit".
        return [
            "({$alias}.lastOrderedAt >= :limit OR ({$alias}.lastOrderedAt IS NULL AND {$alias}.createdAt >= :limit))",
            ['limit' => $this->getLimitDate()],
        ];
    }
}
