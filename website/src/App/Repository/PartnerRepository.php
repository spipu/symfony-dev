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

namespace App\Repository;

use App\Model\Partner;
use Spipu\ApiPartnerBundle\Entity\PartnerInterface;
use Spipu\ApiPartnerBundle\Repository\PartnerRepositoryInterface;
use Spipu\ConfigurationBundle\Service\ConfigurationManager;

class PartnerRepository implements PartnerRepositoryInterface
{
    private ConfigurationManager $configurationManager;
    private Partner $partner;

    public function __construct(
        ConfigurationManager $configurationManager
    ) {
        $this->configurationManager = $configurationManager;

        $this->initPartner();
    }

    private function initPartner()
    {
        $this->partner = new Partner(
            (int) $this->configurationManager->get('api.partner.enabled') === 1,
            (string) $this->configurationManager->get('api.partner.api_key'),
            (string) $this->configurationManager->getEncrypted('api.partner.api_secret')
        );
    }

    public function getAllPartners(): array
    {
        return [$this->partner];
    }

    public function getPartnerByApiKey(string $apiKey): ?PartnerInterface
    {
        if ($apiKey !== $this->partner->getApiKey()) {
            return null;
        }

        return $this->partner;
    }

    public function getPartnerById(int $id): ?PartnerInterface
    {
        if ($id !== $this->partner->getId()) {
            return null;
        }

        return $this->partner;
    }
}
