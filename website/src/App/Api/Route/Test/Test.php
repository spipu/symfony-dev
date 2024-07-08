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
use DateTime;
use Spipu\ApiPartnerBundle\Model\Parameter\ArrayParameter;
use Spipu\ApiPartnerBundle\Model\Parameter\BooleanParameter;
use Spipu\ApiPartnerBundle\Model\Parameter\DateTimeParameter;
use Spipu\ApiPartnerBundle\Model\Parameter\IntegerParameter;
use Spipu\ApiPartnerBundle\Model\Parameter\NumberParameter;
use Spipu\ApiPartnerBundle\Model\Parameter\ObjectParameter;
use Spipu\ApiPartnerBundle\Model\Parameter\StringParameter;
use Spipu\ApiPartnerBundle\Model\ParameterInterface;
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
            'test_id' => (new IntegerParameter())->setMinValue(0, true),
        ];
    }

    /**
     * @return ParameterInterface[]
     */
    public function getQueryParameters(): array
    {
        return [
            'name' => (new StringParameter())->setRequired(true)->setMinLength(1),
        ];
    }

    /**
     * @return ParameterInterface[]
     */
    public function getBodyParameters(): array
    {
        return [
            'required_rows' => (new ArrayParameter())->setRequired(true)->setMinItems(1)->setItemParameter(
                (new ObjectParameter())
                    ->addProperty('test_boolean', (new BooleanParameter())->setRequired(true))
                    ->addProperty('test_datetime', (new DateTimeParameter())->setRequired(true))
                    ->addProperty('test_integer', (new IntegerParameter())->setRequired(true)->setMinValue(2))
                    ->addProperty('test_number', (new NumberParameter())->setRequired(true)->setMinValue(3))
                    ->addProperty('test_string', (new StringParameter())->setRequired(true)->setMinLength(4))
            ),
            'optional_rows' => (new ArrayParameter())->setRequired(false)->setMinItems(0)->setItemParameter(
                (new ObjectParameter())
                    ->addProperty('test_boolean', (new BooleanParameter())->setRequired(false))
                    ->addProperty('test_datetime', (new DateTimeParameter())->setRequired(false))
                    ->addProperty('test_integer', (new IntegerParameter())->setRequired(false))
                    ->addProperty('test_number', (new NumberParameter())->setRequired(false))
                    ->addProperty('test_string', (new StringParameter())->setRequired(false))
            ),
            'default_rows' => (new ArrayParameter())->setRequired(false)->setMinItems(0)->setItemParameter(
                (new ObjectParameter())
                    ->addProperty('test_boolean', (new BooleanParameter())->setRequired(false)->setDefaultValue(false))
                    ->addProperty('test_datetime', (new DateTimeParameter())->setRequired(false)->setDefaultValue(new DateTime()))
                    ->addProperty('test_integer', (new IntegerParameter())->setRequired(false)->setDefaultValue(42))
                    ->addProperty('test_number', (new NumberParameter())->setRequired(false)->setDefaultValue(42.42))
                    ->addProperty('test_string', (new StringParameter())->setRequired(false)->setDefaultValue('spipu'))
            ),
        ];
    }
}
