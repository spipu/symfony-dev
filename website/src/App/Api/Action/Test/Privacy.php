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

use Spipu\ApiPartnerBundle\Api\ActionInterface;
use Spipu\ApiPartnerBundle\Model\Context;
use Spipu\ApiPartnerBundle\Model\Response;

class Privacy implements ActionInterface
{
    public function execute(Context $context): Response
    {
        $response = new Response();
        $response
            ->setCode(200)
            ->setContentJson([
                'customer_id' => $context->getPathParameter('customer_id'),
                'email'       => $context->getQueryParameter('email'),
                'customer'    => $context->getBodyParameter('customer'),
            ])
        ;

        return $response;
    }
}
