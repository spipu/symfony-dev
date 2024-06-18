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
class Test extends AbstractRoute
{
    public function getDescription(): ?string
    {
        return "
            <p>Allows you to test the API</p>
        ";
    }

    public function getRoutePattern(): string
    {
        return '/test/(?<test_id>[0-9]+)';
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
            'test_id' => (new SpipuParameter\IntegerParameter())->setMinValue(0, true),
        ];
    }

    /**
     * @return ParameterInterface[]
     */
    public function getQueryParameters(): array
    {
        return [
            'name' => (new SpipuParameter\StringParameter())->setRequired(true)->setMinLength(1),
        ];
    }

    /**
     * @return ParameterInterface[]
     */
    public function getBodyParameters(): array
    {
        return [
            'required_rows' => $this->getTestParameters(true),
            'optional_rows' => $this->getTestParameters(false),
        ];
    }

    public function getResponseFormat(): ?ResponseFormat
    {
        return (new ResponseFormat('json'))->setJsonContent([
            'test'   => (new SpipuParameter\StringParameter())->setRequired(true),
            'params' => (new SpipuParameter\ObjectParameter())
                ->addProperty('test_id', (new SpipuParameter\IntegerParameter())->setMinValue(0, true))
                ->addProperty('name', (new SpipuParameter\StringParameter())->setRequired(true)->setMinLength(1))
                ->addProperty('required_rows', $this->getTestParameters(true))
                ->addProperty('optional_rows', $this->getTestParameters(false))
            ,
        ]);
    }

    private function getTestParameters(bool $required): ParameterInterface
    {
        return (new SpipuParameter\ArrayParameter())
            ->setRequired($required)
            ->setMinItems($required ? 1 : 0)
            ->setItemParameter(
                (new SpipuParameter\ObjectParameter())
                    ->addProperty('test_boolean', (new SpipuParameter\BooleanParameter())->setRequired($required))
                    ->addProperty('test_datetime', (new SpipuParameter\DateTimeParameter())->setRequired($required))
                    ->addProperty('test_integer', (new SpipuParameter\IntegerParameter())->setRequired($required))
                    ->addProperty('test_number', (new SpipuParameter\NumberParameter())->setRequired($required))
                    ->addProperty('test_string', (new SpipuParameter\StringParameter())->setRequired($required))
            )
        ;
    }
}
