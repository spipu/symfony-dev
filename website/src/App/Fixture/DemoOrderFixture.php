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

namespace App\Fixture;

use App\Entity\DemoCustomer;
use App\Entity\DemoOrder;
use App\Repository\DemoCustomerRepository;
use App\Repository\DemoOrderRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Spipu\CoreBundle\Fixture\FixtureInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DemoOrderFixture implements FixtureInterface
{
    private EntityManagerInterface $entityManager;
    private DemoOrderRepository $demoOrderRepository;
    private DemoCustomerRepository $demoCustomerRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        DemoOrderRepository $demoOrderRepository,
        DemoCustomerRepository $demoCustomerRepository
    ) {
        $this->entityManager = $entityManager;
        $this->demoOrderRepository = $demoOrderRepository;
        $this->demoCustomerRepository = $demoCustomerRepository;
    }

    public function getCode(): string
    {
        return 'demo-order';
    }

    public function getOrder(): int
    {
        return 9000;
    }

    public function load(OutputInterface $output): void
    {
        $output->writeln("Add Demo Orders");

        if ($this->demoOrderRepository->count([]) > 0) {
            $output->writeln("  => Already added");
            return;
        }

        $customers = [];
        foreach ($this->getCustomersData() as $code => $data) {
            $customers[$code] = (new DemoCustomer())
                ->setName($data['name'])
                ->setEmail($data['email'])
                ->setPhone($data['phone'])
            ;
            $this->entityManager->persist($customers[$code]);
        }

        $now = new DateTimeImmutable();
        foreach ($this->getOrdersData() as $data) {
            $hiddenAt = ($data['hidden_at'] === null ? null : $now->modify($data['hidden_at']));
            $order = (new DemoOrder())
                ->setReference($data['reference'])
                ->setCustomer($customers[$data['customer']])
                ->setStatus($data['status'])
                ->setAmount($data['amount'])
                ->setCity($data['city'])
                ->setPersonalDataHiddenAt($hiddenAt)
            ;
            $this->entityManager->persist($order);
        }

        $this->entityManager->flush();
    }

    public function remove(OutputInterface $output): void
    {
        $output->writeln("Remove Demo Orders");

        $this->demoOrderRepository->deleteAll();
        $this->demoCustomerRepository->deleteAll();
    }

    private function getCustomersData(): array
    {
        return [
            'C1' => ['name' => 'Alice Martin', 'email' => 'alice.martin@example.com', 'phone' => '+33600000001'],
            'C2' => ['name' => 'Bob Durand', 'email' => 'bob.durand@example.com', 'phone' => '+33600000002'],
            'C3' => ['name' => 'Carla Rossi', 'email' => 'carla.rossi@example.com', 'phone' => '+33600000003'],
        ];
    }

    private function getOrdersData(): array
    {
        return [
            $this->getOrderData('ORD-001', 'C1', 'shipped', 120.5, 'Paris', '-30 days'),
            $this->getOrderData('ORD-002', 'C1', 'paid', 80., 'Lyon', '+30 days'),
            $this->getOrderData('ORD-003', 'C1', 'new', 15.99, 'Paris', null),
            $this->getOrderData('ORD-004', 'C2', 'shipped', 200., 'Marseille', '-1 day'),
            $this->getOrderData('ORD-005', 'C2', 'paid', 42., 'Lyon', '+1 day'),
            $this->getOrderData('ORD-006', 'C2', 'new', 9.9, 'Nantes', null),
            $this->getOrderData('ORD-007', 'C3', 'shipped', 310., 'Paris', '-365 days'),
            $this->getOrderData('ORD-008', 'C3', 'cancelled', 55., 'Lille', '+365 days'),
            $this->getOrderData('ORD-009', 'C3', 'new', 70., 'Nantes', null),
        ];
    }

    private function getOrderData(
        string $reference,
        string $customer,
        string $status,
        float $amount,
        string $city,
        ?string $hiddenAt
    ): array {
        return [
            'reference' => $reference,
            'customer'  => $customer,
            'status'    => $status,
            'amount'    => $amount,
            'city'      => $city,
            'hidden_at' => $hiddenAt,
        ];
    }
}
