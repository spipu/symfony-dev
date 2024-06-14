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

use Spipu\ApiPartnerBundle\Service\ApiControllerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class ApiEntryPointController extends AbstractController
{
    #[Route(path: '{route<.*>}', name: 'api_partner_entrypoint')]
    public function entryPointAction(
        ApiControllerService $apiControllerService,
        SymfonyRequest $symfonyRequest,
        string $route
    ): Response {
        return $apiControllerService->entryPointAction($symfonyRequest, $route);
    }
}
