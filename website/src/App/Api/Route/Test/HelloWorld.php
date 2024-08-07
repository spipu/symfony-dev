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

namespace App\Api\Route\Test;

use App\Api\Route\AbstractRoute;
use Spipu\ApiPartnerBundle\Model\Parameter as SpipuParameter;
use Spipu\ApiPartnerBundle\Model\ParameterInterface;
use Spipu\ApiPartnerBundle\Model\ResponseFormat;
use Symfony\Component\HttpFoundation\Request;

// phpcs:disable Generic.Files.LineLength.TooLong
class HelloWorld extends AbstractRoute
{
    public function getDescription(): ?string
    {
        return "
            <p>Health Check - HelloWorld</p>
        ";
    }

    public function getRoutePattern(): string
    {
        return '/hello_world';
    }

    public function getHttpMethod(): string
    {
        return Request::METHOD_GET;
    }

    /**
     * @return ParameterInterface[]
     */
    public function getQueryParameters(): array
    {
        return [
            'name' => (new SpipuParameter\StringParameter())->setRequired(true)->setMinLength(1)->setMaxLength(100),
        ];
    }

    public function getResponseFormat(): ?ResponseFormat
    {
        return new ResponseFormat('text');
    }
}
