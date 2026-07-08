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

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Spipu\CoreBundle\Controller\AbstractController;
use Spipu\UserBundle\Entity\UserInterface;
use Spipu\UserBundle\Repository\UserRepository;
use Spipu\UserBundle\Service\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Demo controller showing how a consumer project can wire user grid row/mass actions.
 * These endpoints are voluntarily kept out of the UserBundle; the secure single-user
 * enable/disable/reset lives in the bundle (POST + CSRF, from the user show page).
 *
 * @method UserInterface getUser()
 */
#[Route(path: '/user/grid')]
class UserGridActionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserManager $userManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserManager $userManager
    ) {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    #[Route(path: '/enable/{id}', name: 'app_user_grid_enable', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN_MANAGE_USER_EDIT')]
    public function enable(UserRepository $userRepository, int $id): Response
    {
        return $this->toggle($userRepository, $id, 'enable', 'spipu.user.success.enabled');
    }

    #[Route(path: '/disable/{id}', name: 'app_user_grid_disable', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN_MANAGE_USER_EDIT')]
    public function disable(UserRepository $userRepository, int $id): Response
    {
        return $this->toggle($userRepository, $id, 'disable', 'spipu.user.success.disabled');
    }

    #[Route(path: '/mass-enable', name: 'app_user_grid_mass_enable', methods: 'POST')]
    #[IsGranted('ROLE_ADMIN_MANAGE_USER_EDIT')]
    public function massEnable(UserRepository $userRepository, Request $request): Response
    {
        return $this->massAction($userRepository, $request, 'enable', 'spipu.user.success.mass_enabled');
    }

    #[Route(path: '/mass-disable', name: 'app_user_grid_mass_disable', methods: 'POST')]
    #[IsGranted('ROLE_ADMIN_MANAGE_USER_EDIT')]
    public function massDisable(UserRepository $userRepository, Request $request): Response
    {
        return $this->massAction($userRepository, $request, 'disable', 'spipu.user.success.mass_disabled');
    }

    private function toggle(UserRepository $userRepository, int $id, string $action, string $transLabel): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var UserInterface $resource */
        $resource = $userRepository->findOneBy(['id' => $id]);
        if (!$resource) {
            throw $this->createNotFoundException();
        }

        if (!$this->toggleUser($resource, $action)) {
            return $this->redirectToRoute('spipu_user_admin_list');
        }

        try {
            $this->entityManager->persist($resource);
            $this->entityManager->flush();
            $this->addFlashTrans('success', $transLabel);
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('spipu_user_admin_list');
    }

    private function massAction(
        UserRepository $userRepository,
        Request $request,
        string $action,
        string $transLabel
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $selected = json_decode((string) $request->request->get('selected', ''));
        if (!is_array($selected) || count($selected) < 1) {
            $this->addFlashTrans('warning', 'spipu.ui.grid.item.at_least_one');
            return $this->redirectToRoute('spipu_user_admin_list');
        }

        $count = 0;
        /** @var UserInterface[] $rows */
        $rows = $userRepository->findBy(['id' => $selected]);
        foreach ($rows as $row) {
            if ($this->toggleUser($row, $action)) {
                $this->entityManager->persist($row);
                $count++;
            }
        }
        $this->entityManager->flush();

        $this->addFlashTrans('success', $transLabel, ['%count' => $count]);

        return $this->redirectToRoute('spipu_user_admin_list');
    }

    private function toggleUser(UserInterface $row, string $action): bool
    {
        if ($this->getUser()->getId() === $row->getId()) {
            $this->addFlashTrans('danger', 'spipu.user.error.yourself_' . $action);
            return false;
        }

        if ($action === 'enable' && !$row->getActive()) {
            $this->userManager->enableUser($row);
            return true;
        }

        if ($action === 'disable' && $row->getActive()) {
            $this->userManager->disableUser($row);
            return true;
        }

        return false;
    }
}
