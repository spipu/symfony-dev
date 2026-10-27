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

use App\Entity\DemoOrderAddress;
use App\Repository\DemoOrderRepository;
use App\Ui\DemoOrderGrid;
use Spipu\CoreBundle\Controller\AbstractController;
use Spipu\UiBundle\Service\Ui\Grid\DataProvider\Doctrine;
use Spipu\UiBundle\Service\Ui\GridFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/demo/order')]
#[IsGranted('ROLE_ADMIN')]
class DemoOrderController extends AbstractController
{
    #[Route(path: '/', name: 'app_demo_order_list', methods: 'GET')]
    public function index(GridFactory $gridFactory, DemoOrderGrid $demoOrderGrid): Response
    {
        $manager = $gridFactory->create($demoOrderGrid);
        $manager->setRoute('app_demo_order_list');

        /** @var Doctrine $dataProvider */
        $dataProvider = $manager->getDataProvider();
        $dataProvider->addJoin('customer', 'left');

        if ($manager->validate()) {
            return $this->redirectToRoute('app_demo_order_list');
        }

        return $this->render('demo_order/index.html.twig', ['manager' => $manager]);
    }

    #[Route(path: '/show/{id}', name: 'app_demo_order_show', methods: 'GET')]
    public function show(DemoOrderRepository $demoOrderRepository, int $id): Response
    {
        $resource = $demoOrderRepository->findOneBy(['id' => $id]);
        if (!$resource) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'demo_order/show.html.twig',
            [
                'order'           => $resource,
                'shippingAddress' => $resource->getAddress(DemoOrderAddress::TYPE_SHIPPING),
                'billingAddress'  => $resource->getAddress(DemoOrderAddress::TYPE_BILLING),
            ]
        );
    }
}
