<?php

declare(strict_types=1);

namespace App\Controller;

use App\Ui\AdminDashboard;
use Spipu\DashboardBundle\Entity\DashboardAcl;
use Spipu\DashboardBundle\Exception\WidgetException;
use Spipu\DashboardBundle\Service\DashboardControllerService;
use Spipu\UiBundle\Exception\UiException;
use Spipu\UserBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard/{action}/{id?}", name="app_dashboard")
     * @param DashboardControllerService $dashboardControllerService
     * @param AdminDashboard $dashboard
     * @param UserRepository $userRepository
     * @param string $action
     * @param int|null $id
     * @return Response
     * @throws UiException
     * @throws WidgetException
     */
    public function main(
        DashboardControllerService $dashboardControllerService,
        AdminDashboard $dashboard,
        UserRepository $userRepository,
        string $action = '',
        ?int $id = null
    ): Response {
        $dashboardAcl = new DashboardAcl();
        $dashboardAcl
            ->configure(
                $this->isGranted('ROLE_ADMIN'),
                $this->isGranted('ROLE_ADMIN'),
                $this->isGranted('ROLE_ADMIN'),
                $this->isGranted('ROLE_ADMIN')
            )
            ->setDefaultUser($userRepository->find(1))
        ;

        return $dashboardControllerService->dispatch($dashboard, 'app_dashboard', $action, $id, $dashboardAcl);
    }
}
