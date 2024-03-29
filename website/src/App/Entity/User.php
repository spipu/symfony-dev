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

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spipu\UserBundle\Entity\AbstractUser;

#[ORM\Entity(repositoryClass: 'Spipu\UserBundle\Repository\UserRepository')]
#[ORM\Table(name: "spipu_user")]
class User extends AbstractUser
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $middleName = null;

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }
}
