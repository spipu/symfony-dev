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

class HelloWorld implements ActionInterface
{
    /**
     * @param Context $context
     * @return Response
     * @throws ApiException
     */
    public function execute(Context $context): Response
    {
        $response = new Response();
        $response->setCode(200)->setContentText(
            sprintf(
                'Hello World [%s] from [%s]',
                $context->getQueryParameter('name'),
                $context->getPartner()->getApiName()
            )
        );

        return $response;
    }
}
