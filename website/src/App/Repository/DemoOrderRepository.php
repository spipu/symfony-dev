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

namespace App\Repository;

use App\Entity\DemoOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemoOrder>
 */
class DemoOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemoOrder::class);
    }

    public function deleteAll(): void
    {
        $this->createQueryBuilder('main')->delete()->getQuery()->execute();
    }
}
