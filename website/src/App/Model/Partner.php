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

namespace App\Model;

use Spipu\ApiPartnerBundle\Entity\PartnerInterface;

class Partner implements PartnerInterface
{
    private bool $enabled;
    private string $apiKey;
    private string $apiSecret;

    public function __construct(
        bool $enabled,
        string $apiKey,
        string $apiSecret
    ) {
        $this->enabled = $enabled;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function getId(): int
    {
        return 1;
    }

    public function getApiName(): string
    {
        return 'Api User';
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getApiSecretKey(): string
    {
        return $this->apiSecret;
    }

    public function isApiEnabled(): bool
    {
        return $this->enabled;
    }
}
