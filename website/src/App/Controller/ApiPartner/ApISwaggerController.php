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

namespace App\Controller\ApiPartner;

use App\Service\ApiPartner\ApiDocumentationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Spipu\ApiPartnerBundle\Service\ApiSwagger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api_partner/swagger")
 */
class ApISwaggerController extends AbstractController
{
    /**
     * @Route(
     *     "/{type}/{code}",
     *     name="app_api_partner_swagger",
     *     methods="GET"
     * )
     * @Security("is_granted('ROLE_ADMIN_API_PARTNER')")
     */
    public function index(
        ApiSwagger $apiSwagger,
        ApiDocumentationService $apiDocumentationService,
        ?string $type = null,
        ?string $code = null
    ): Response {
        return $this->render(
            'api_partner/swagger.html.twig',
            [
                'groupedRouteCodes' => $apiSwagger->getGroupedRouteCodes(),
                'documents'         => $apiDocumentationService->getDocuments(),
                'version'           => $apiDocumentationService->getVersion(),
                'currentDoc'        => ($type === 'doc' ? $apiDocumentationService->getDocument($code) : null),
                'currentRoute'      => ($type === 'route' ? $apiSwagger->getRoute($code) : null),
            ]
        );
    }
}
