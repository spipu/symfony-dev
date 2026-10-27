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
use App\Entity\DemoOrderAddress;
use App\Repository\DemoCustomerRepository;
use App\Repository\DemoOrderAddressRepository;
use App\Repository\DemoOrderRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Spipu\CoreBundle\Fixture\FixtureInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Demo customers and orders, with random old dates to show the personal data protection
 * (demo.personal_data.hide_after = 1095 days by default).
 * The random generator is seeded: the generated data are the same on each load.
 */
class DemoOrderFixture implements FixtureInterface
{
    private const RANDOM_SEED = 42;
    private const NB_CUSTOMERS = 50;
    private const NB_CUSTOMERS_WITH_ORDERS = 40;
    private const STATUSES = ['new', 'paid', 'shipped', 'cancelled'];
    private const FIRSTNAMES = [
        'Alice',
        'Bob',
        'Carla',
        'David',
        'Emma',
        'Fabien',
        'Gaelle',
        'Hugo',
        'Ines',
        'Julien',
        'Karine',
        'Louis',
        'Manon',
        'Nicolas',
        'Oceane',
        'Paul',
        'Quentin',
        'Rose',
        'Sacha',
        'Thomas',
    ];
    private const LASTNAMES = [
        'Bernard',
        'Blanc',
        'Bonnet',
        'Dubois',
        'Durand',
        'Faure',
        'Fournier',
        'Garcia',
        'Girard',
        'Guerin',
        'Laurent',
        'Lefebvre',
        'Leroy',
        'Martin',
        'Mercier',
        'Michel',
        'Moreau',
        'Petit',
        'Rossi',
        'Simon',
    ];
    private const STREETS = [
        'allee des Tilleuls',
        'avenue Victor Hugo',
        'boulevard Voltaire',
        'chemin des Vignes',
        'place de la Mairie',
        'rue de la Paix',
        'rue du Moulin',
        'rue Pasteur',
    ];
    private const CITIES = [
        ['06000', 'Nice'],
        ['13001', 'Marseille'],
        ['31000', 'Toulouse'],
        ['33000', 'Bordeaux'],
        ['35000', 'Rennes'],
        ['44000', 'Nantes'],
        ['59000', 'Lille'],
        ['67000', 'Strasbourg'],
        ['69002', 'Lyon'],
        ['75001', 'Paris'],
    ];

    private EntityManagerInterface $entityManager;
    private DemoOrderRepository $demoOrderRepository;
    private DemoOrderAddressRepository $demoOrderAddressRepository;
    private DemoCustomerRepository $demoCustomerRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        DemoOrderRepository $demoOrderRepository,
        DemoOrderAddressRepository $demoOrderAddressRepository,
        DemoCustomerRepository $demoCustomerRepository
    ) {
        $this->entityManager = $entityManager;
        $this->demoOrderRepository = $demoOrderRepository;
        $this->demoOrderAddressRepository = $demoOrderAddressRepository;
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

        if ($this->demoCustomerRepository->count([]) > 0) {
            $output->writeln("  => Already added");
            return;
        }

        mt_srand(self::RANDOM_SEED);

        $customers = [];
        $orders = [];
        $orderNumber = 0;
        for ($index = 1; $index <= self::NB_CUSTOMERS; $index++) {
            $data = $this->generateCustomerData($index);
            $customer = $this->createCustomer($data);
            $this->entityManager->persist($customer);
            $customers[] = [$customer, $data['created']];

            if ($index > self::NB_CUSTOMERS_WITH_ORDERS) {
                continue;
            }

            $nbOrders = mt_rand(1, 3);
            for ($orderIndex = 0; $orderIndex < $nbOrders; $orderIndex++) {
                $orderData = $this->generateOrderData(++$orderNumber, $data['created']);
                $orders[] = [$this->createOrder($customer, $orderData), $orderData['created']];
            }
        }

        $this->entityManager->flush();

        $this->moveDatesToThePast($customers, $orders);
    }

    private function createCustomer(array $data): DemoCustomer
    {
        $birthday = (($data['birthday'] === null) ? null : new DateTimeImmutable($data['birthday']));

        return (new DemoCustomer())
            ->setFirstname($data['firstname'])
            ->setLastname($data['lastname'])
            ->setEmail($data['email'])
            ->setPhone($data['phone'])
            ->setBirthday($birthday)
        ;
    }

    private function createOrder(DemoCustomer $customer, array $data): DemoOrder
    {
        $order = (new DemoOrder())
            ->setCustomer($customer)
            ->setReference($data['reference'])
            ->setStatus($data['status'])
            ->setAmount($data['amount'])
            ->setCustomerFirstName($customer->getFirstname())
            ->setCustomerLastName($customer->getLastname())
            ->setCustomerEmail($customer->getEmail())
        ;

        foreach ([DemoOrderAddress::TYPE_SHIPPING, DemoOrderAddress::TYPE_BILLING] as $type) {
            $order->addAddress(
                (new DemoOrderAddress())
                    ->setType($type)
                    ->setFirstname($customer->getFirstname())
                    ->setLastname($customer->getLastname())
                    ->setEmail($customer->getEmail())
                    ->setPhone($customer->getPhone())
                    ->setAddress($data['address'][$type][0])
                    ->setPostalCode($data['address'][$type][1])
                    ->setCity($data['address'][$type][2])
                    ->setCountry('France')
            );
        }

        $this->entityManager->persist($order);

        return $order;
    }

    /**
     * The creation dates are set by TimestampableTrait: they are moved to the past after the insert.
     * The integers are the numbers of days in the past.
     * @param array<array{0: DemoCustomer, 1: int}> $customers
     * @param array<array{0: DemoOrder, 1: int}>    $orders
     */
    private function moveDatesToThePast(array $customers, array $orders): void
    {
        $connection = $this->entityManager->getConnection();
        $now = new DateTimeImmutable();

        foreach ($orders as [$order, $created]) {
            $date = $now->modify('-' . $created . ' days')->format('Y-m-d H:i:s');
            $connection->executeStatement(
                'UPDATE demo_order SET created_at = ?, updated_at = ? WHERE id = ?',
                [$date, $date, $order->getId()]
            );
            $connection->executeStatement(
                'UPDATE demo_order_address SET created_at = ?, updated_at = ? WHERE order_id = ?',
                [$date, $date, $order->getId()]
            );
        }

        foreach ($customers as [$customer, $created]) {
            $date = $now->modify('-' . $created . ' days')->format('Y-m-d H:i:s');
            $connection->executeStatement(
                'UPDATE demo_customer SET created_at = ?, updated_at = ? WHERE id = ?',
                [$date, $date, $customer->getId()]
            );
        }

        $connection->executeStatement(
            'UPDATE demo_customer SET last_ordered_at = (
                SELECT MAX(o.created_at) FROM demo_order o WHERE o.customer_id = demo_customer.id
            )'
        );
    }

    public function remove(OutputInterface $output): void
    {
        $output->writeln("Remove Demo Orders");

        $this->demoOrderAddressRepository->deleteAll();
        $this->demoOrderRepository->deleteAll();
        $this->demoCustomerRepository->deleteAll();
    }

    /**
     * A customer created between 5 and 2000 days ago: with a limit of 1095 days, some of them are hidden.
     */
    private function generateCustomerData(int $index): array
    {
        $firstname = self::FIRSTNAMES[mt_rand(0, count(self::FIRSTNAMES) - 1)];
        $lastname = self::LASTNAMES[mt_rand(0, count(self::LASTNAMES) - 1)];

        $birthday = null;
        if (mt_rand(1, 10) <= 7) {
            $birthday = sprintf('%04d-%02d-%02d', mt_rand(1950, 2005), mt_rand(1, 12), mt_rand(1, 28));
        }

        return [
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'email'     => strtolower($firstname . '.' . $lastname . '.' . $index) . '@example.com',
            'phone'     => sprintf('+336%08d', $index),
            'birthday'  => $birthday,
            'created'   => mt_rand(5, 2000),
        ];
    }

    /**
     * An order is always created after its customer.
     */
    private function generateOrderData(int $number, int $customerCreated): array
    {
        $shippingAddress = $this->generateAddress();
        $billingAddress = ((mt_rand(1, 10) <= 7) ? $shippingAddress : $this->generateAddress());

        return [
            'reference' => sprintf('ORD-%04d', $number),
            'status'    => self::STATUSES[mt_rand(0, count(self::STATUSES) - 1)],
            'amount'    => mt_rand(500, 50000) / 100,
            'created'   => mt_rand(0, $customerCreated),
            'address'   => [
                DemoOrderAddress::TYPE_SHIPPING => $shippingAddress,
                DemoOrderAddress::TYPE_BILLING  => $billingAddress,
            ],
        ];
    }

    /**
     * @return string[] [address, postal code, city]
     */
    private function generateAddress(): array
    {
        [$postalCode, $city] = self::CITIES[mt_rand(0, count(self::CITIES) - 1)];
        $street = self::STREETS[mt_rand(0, count(self::STREETS) - 1)];

        return [mt_rand(1, 150) . ' ' . $street, $postalCode, $city];
    }
}
