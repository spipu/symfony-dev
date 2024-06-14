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

namespace App\Api\Action\Test;

use Spipu\ApiPartnerBundle\Exception\ApiException;
use Spipu\ApiPartnerBundle\Api\ActionInterface;
use Spipu\ApiPartnerBundle\Model\Context;
use Spipu\ApiPartnerBundle\Model\Response;

class Test implements ActionInterface
{
    /**
     * @param Context $context
     * @return Response
     * @throws ApiException
     */
    public function execute(Context $context): Response
    {
        $response = new Response();
        $response->setCode(200)->setContentJson(
            [
                'test'          => 'ok',
                'params' => [
                    'test_id'       => $context->getPathParameter('test_id'),
                    'name'          => $context->getQueryParameter('name'),
                    'required_rows' => $context->getBodyParameter('required_rows'),
                    'optional_rows' => $context->getBodyParameter('optional_rows'),
                ],
            ]
        );

        return $response;
    }
}
