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

namespace App\Api\Route;

use Spipu\ApiPartnerBundle\Api\RouteInterface;
use Spipu\ApiPartnerBundle\Model\ParameterInterface;
use Spipu\ApiPartnerBundle\Model\ResponseFormat;

abstract class AbstractRoute implements RouteInterface
{
    protected ?string $code = null;
    protected ?string $actionServiceName = null;

    public function getCode(): string
    {
        if ($this->code === null) {
            $this->code = str_replace('\\', '-', explode('\\Route\\', get_class($this))[1]);
        }

        return $this->code;
    }

    public function getActionServiceName(): string
    {
        if ($this->actionServiceName === null) {
            $this->actionServiceName = str_replace('\\Route\\', '\\Action\\', get_class($this));
        }
        return $this->actionServiceName;
    }

    /**
     * @return ParameterInterface[]
     */
    public function getPathParameters(): array
    {
        return [];
    }

    /**
     * @return ParameterInterface[]
     */
    public function getQueryParameters(): array
    {
        return [];
    }

    /**
     * @return ParameterInterface[]
     */
    public function getBodyParameters(): array
    {
        return [];
    }

    public function getResponseFormat(): ?ResponseFormat
    {
        return null;
    }

    public function isDeprecated(): bool
    {
        return false;
    }

    public function getDescription(): ?string
    {
        return null;
    }
}
