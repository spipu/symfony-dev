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

class Privacy extends AbstractRoute
{
    public function getDescription(): ?string
    {
        return "
            <p>Privacy parameters demo: values are logged as ###PRIVACY###</p>
        ";
    }

    public function getRoutePattern(): string
    {
        return '/privacy/(?<customer_id>[0-9]+)';
    }

    public function getHttpMethod(): string
    {
        return Request::METHOD_POST;
    }

    /**
     * @return ParameterInterface[]
     */
    public function getPathParameters(): array
    {
        return [
            'customer_id' => (new SpipuParameter\IntegerParameter())->setPrivacy(true),
        ];
    }

    /**
     * @return ParameterInterface[]
     */
    public function getQueryParameters(): array
    {
        return [
            'email' => (new SpipuParameter\StringParameter())->setRequired(true)->setMinLength(1)->setPrivacy(true),
        ];
    }

    /**
     * @return ParameterInterface[]
     */
    public function getBodyParameters(): array
    {
        return [
            'customer' => $this->getCustomerParameter(),
        ];
    }

    public function getResponseFormat(): ?ResponseFormat
    {
        return (new ResponseFormat('json'))->setJsonContent([
            'customer_id' => (new SpipuParameter\IntegerParameter())->setRequired(true)->setPrivacy(true),
            'email'       => (new SpipuParameter\StringParameter())->setRequired(true)->setPrivacy(true),
            'customer'    => $this->getCustomerParameter(),
        ]);
    }

    private function getCustomerParameter(): SpipuParameter\ObjectParameter
    {
        return (new SpipuParameter\ObjectParameter())
            ->addProperty('full_name', (new SpipuParameter\StringParameter())->setRequired(true)->setPrivacy(true))
            ->addProperty('newsletter', new SpipuParameter\BooleanParameter())
            ->setRequired(true)
        ;
    }
}
