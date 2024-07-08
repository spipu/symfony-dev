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
        $data = [
            'test'   => 'ok',
            'params' => [
                'test_id'       => $context->getPathParameter('test_id'),
                'name'          => $context->getQueryParameter('name'),
                'required_rows' => $context->getBodyParameter('required_rows'),
                'optional_rows' => $context->getBodyParameter('optional_rows'),
                'default_rows'  => $context->getBodyParameter('default_rows'),
            ],
        ];

        foreach ($data['params']['required_rows'] as &$requiredRow) {
            $requiredRow['test_datetime'] = $requiredRow['test_datetime']->format('Y-m-d H:i:s');
        }

        if (!empty($data['params']['optional_rows'])) {
            foreach ($data['params']['optional_rows'] as &$optionalRow) {
                if (!empty($optionalRow['test_datetime'])) {
                    $optionalRow['test_datetime'] = $optionalRow['test_datetime']->format('Y-m-d H:i:s');
                }
            }
        }

        if (!empty($data['params']['default_rows'])) {
            foreach ($data['params']['default_rows'] as &$defaultRow) {
                if (!empty($defaultRow['test_datetime'])) {
                    $defaultRow['test_datetime'] = $defaultRow['test_datetime']->format('Y-m-d H:i:s');
                }
            }
        }

        $response = new Response();
        $response
            ->setCode(200)
            ->setContentJson($data)
        ;

        return $response;
    }
}
