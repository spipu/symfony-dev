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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Spipu\ApiPartnerBundle\Repository\ApiLogPartnerRepository;
use Spipu\ApiPartnerBundle\Repository\PartnerRepositoryInterface;
use Spipu\ApiPartnerBundle\Ui\ApiPartnerLogGrid;
use Spipu\UiBundle\Entity\Grid\Action;
use Spipu\UiBundle\Service\Ui\GridFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api_partner/log")
 */
class ApILogController extends AbstractController
{
    /**
     * @Route(
     *     "/",
     *     name="app_api_partner_log_list",
     *     methods="GET"
     * )
     * @Security("is_granted('ROLE_ADMIN_API_PARTNER')")
     */
    public function index(
        GridFactory $gridFactory,
        ApiPartnerLogGrid $apiPartnerLogGrid
    ): Response {
        $apiPartnerLogGrid
            ->getDefinition()
            ->addRowAction(
                (new Action('show', 'spipu.ui.action.show', 10, 'app_api_partner_log_show'))
                    ->setCssClass('primary')
                    ->setIcon('eye')
                    ->setNeededRole('ROLE_ADMIN_API_PARTNER')
            )
        ;

        $manager = $gridFactory->create($apiPartnerLogGrid);
        $manager->setRoute('app_api_partner_log_list');
        if ($manager->validate()) {
            return $this->redirectToRoute('app_api_partner_log_list');
        }

        return $this->render('api_partner/log_list.html.twig', ['manager' => $manager]);
    }

    /**
     * @Route(
     *     "/show/{id}",
     *     name="app_api_partner_log_show",
     *     methods="GET"
     * )
     * @Security("is_granted('ROLE_ADMIN_API_PARTNER')")
     */
    public function show(
        ApiLogPartnerRepository $apiLogPartnerRepository,
        PartnerRepositoryInterface $partnerRepository,
        int $id
    ): Response {
        $log = $apiLogPartnerRepository->findOneBy(['id' => $id]);
        if (!$log) {
            throw $this->createNotFoundException();
        }
        $partner = $partnerRepository->getPartnerById($log->getPartnerId());

        return $this->render(
            'api_partner/log_show.html.twig',
            [
                'log'      => $log,
                'partner'  => $partner,
                'hideSensitiveData' => false,
            ]
        );
    }
}
