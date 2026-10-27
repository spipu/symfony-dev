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

use DateTimeImmutable;
use Spipu\ConfigurationBundle\Service\ConfigurationManager;

class DemoConfiguration
{
    private ConfigurationManager $configurationManager;
    private ?DateTimeImmutable $personalDataLimitDate = null;

    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    public function getLimitDate(): DateTimeImmutable
    {
        if ($this->personalDataLimitDate === null) {
            $days = $this->getPersonalDataHideAfterDays();
            $this->personalDataLimitDate = new DateTimeImmutable('-' . $days . ' days');
        }

        return $this->personalDataLimitDate;
    }

    private function getPersonalDataHideAfterDays(): int
    {
        $value = (int) $this->configurationManager->get('demo.personal_data.hide_after');

        return max(1, $value);
    }
}
