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

use App\Service\DemoConfiguration;
use DateTimeImmutable;
use DateTimeInterface;
use Spipu\UiBundle\Service\PersonalData\PersonalDataRuleInterface;

abstract class AbstractDemoRule implements PersonalDataRuleInterface
{
    private DemoConfiguration $demoConfiguration;

    public function __construct(DemoConfiguration $demoConfiguration)
    {
        $this->demoConfiguration = $demoConfiguration;
    }

    public function isActive(): bool
    {
        return true;
    }

    protected function isWithinLimit(DateTimeInterface $date): bool
    {
        return ($date >= $this->getLimitDate());
    }

    /**
     * @return array{0: string, 1: array<string, mixed>}
     */
    protected function getCreatedAtVisibleDqlCondition(string $alias): array
    {
        return ["{$alias}.createdAt >= :limit", ['limit' => $this->getLimitDate()]];
    }

    protected function getLimitDate(): DateTimeImmutable
    {
        return $this->demoConfiguration->getLimitDate();
    }
}
