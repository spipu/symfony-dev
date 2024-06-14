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

namespace App\Api\Action\Settings;

use App\Service\ApiPartner\ApiDocumentationService;
use Spipu\ApiPartnerBundle\Api\ActionInterface;
use Spipu\ApiPartnerBundle\Model\Context;
use Spipu\ApiPartnerBundle\Model\Response;

class Version implements ActionInterface
{
    /**
     * @param Context $context
     * @return Response
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function execute(Context $context): Response
    {
        $response = new Response();
        $response->setCode(200)->setContentJson(['version' => ApiDocumentationService::API_VERSION]);

        return $response;
    }
}
