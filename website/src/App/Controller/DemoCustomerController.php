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

use App\Entity\DemoCustomer;
use App\Repository\DemoCustomerRepository;
use App\Ui\DemoCustomerForm;
use App\Ui\DemoCustomerGrid;
use App\Ui\DemoOrderGrid;
use Spipu\CoreBundle\Controller\AbstractController;
use Spipu\UiBundle\Service\Ui\FormFactory;
use Spipu\UiBundle\Service\Ui\Grid\DataProvider\Doctrine;
use Spipu\UiBundle\Service\Ui\GridFactory;
use Spipu\UiBundle\Service\Ui\PersonalData\PersonalDataProtection;
use Spipu\UiBundle\Service\Ui\ShowFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/demo/customer')]
#[IsGranted('ROLE_ADMIN')]
class DemoCustomerController extends AbstractController
{
    #[Route(path: '/', name: 'app_demo_customer_list', methods: 'GET')]
    public function index(GridFactory $gridFactory, DemoCustomerGrid $demoCustomerGrid): Response
    {
        $manager = $gridFactory->create($demoCustomerGrid);
        $manager->setRoute('app_demo_customer_list');
        if ($manager->validate()) {
            return $this->redirectToRoute('app_demo_customer_list');
        }

        return $this->render('demo_customer/index.html.twig', ['manager' => $manager]);
    }

    #[Route(path: '/show/{id}', name: 'app_demo_customer_show', methods: 'GET')]
    public function show(
        ShowFactory $showFactory,
        DemoCustomerForm $demoCustomerForm,
        DemoCustomerRepository $demoCustomerRepository,
        int $id
    ): Response {
        $resource = $this->getResource($demoCustomerRepository, $id);

        $showManager = $showFactory->create($demoCustomerForm);
        $showManager->setResource($resource);
        $showManager->validate();

        return $this->render(
            'demo_customer/show.html.twig',
            ['showManager' => $showManager, 'customer' => $resource]
        );
    }

    #[Route(path: '/show/{id}/orders', name: 'app_demo_customer_show_tab_orders', methods: 'GET')]
    public function showTabOrders(
        GridFactory $gridFactory,
        DemoOrderGrid $demoOrderGrid,
        DemoCustomerRepository $demoCustomerRepository,
        int $id
    ): Response {
        $resource = $this->getResource($demoCustomerRepository, $id);

        $demoOrderGrid->getDefinition()->removeColumn('customer_birthday');

        $manager = $gridFactory->create($demoOrderGrid);
        $manager->setRoute('app_demo_customer_show_tab_orders', ['id' => $id]);

        /** @var Doctrine $dataProvider */
        $dataProvider = $manager->getDataProvider();
        $dataProvider->addCondition('main.customer = ' . (int) $resource->getId());
        if ($manager->validate()) {
            return $this->redirectToRoute('app_demo_customer_show_tab_orders', ['id' => $id]);
        }

        return $this->render(
            'demo_customer/tabs/orders.html.twig',
            ['manager' => $manager, 'customer' => $resource]
        );
    }

    #[Route(path: '/edit/{id}', name: 'app_demo_customer_edit', methods: 'GET|POST')]
    public function edit(
        FormFactory $formFactory,
        DemoCustomerForm $demoCustomerForm,
        DemoCustomerRepository $demoCustomerRepository,
        int $id
    ): Response {
        $resource = $this->getResource($demoCustomerRepository, $id);

        $manager = $formFactory->create($demoCustomerForm);
        $manager->setResource($resource);
        $manager->setSubmitButton('spipu.ui.action.save');
        if ($manager->validate()) {
            return $this->redirectToRoute('app_demo_customer_show', ['id' => $id]);
        }

        return $this->render('demo_customer/edit.html.twig', ['manager' => $manager]);
    }

    #[Route(path: '/mass-count', name: 'app_demo_customer_mass_count', methods: 'POST')]
    public function massCount(
        DemoCustomerRepository $demoCustomerRepository,
        PersonalDataProtection $personalDataProtection,
        Request $request
    ): Response {
        $selected = json_decode((string) $request->request->get('selected', ''));
        if (!is_array($selected) || (count($selected) < 1)) {
            $this->addFlashTrans('warning', 'spipu.ui.grid.item.at_least_one');
            return $this->redirectToRoute('app_demo_customer_list');
        }

        $count = 0;
        foreach ($demoCustomerRepository->findBy(['id' => $selected]) as $customer) {
            if (!$personalDataProtection->isEntityHidden($customer)) {
                $count++;
            }
        }

        $this->addFlashTrans('success', 'app.demo_customer.success.mass_count', ['%count' => $count]);

        return $this->redirectToRoute('app_demo_customer_list');
    }

    private function getResource(DemoCustomerRepository $demoCustomerRepository, int $id): DemoCustomer
    {
        $resource = $demoCustomerRepository->findOneBy(['id' => $id]);
        if (!$resource) {
            throw $this->createNotFoundException();
        }

        return $resource;
    }
}
