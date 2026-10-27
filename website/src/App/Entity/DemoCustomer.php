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

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Spipu\UiBundle\Entity\EntityInterface;
use Spipu\UiBundle\Entity\TimestampableInterface;
use Spipu\UiBundle\Entity\TimestampableTrait;

#[ORM\Entity(repositoryClass: 'App\Repository\DemoCustomerRepository')]
#[ORM\Table(name: "demo_customer")]
#[ORM\HasLifecycleCallbacks]
class DemoCustomer implements EntityInterface, TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $firstname = '';

    #[ORM\Column(length: 255)]
    private string $lastname = '';

    #[ORM\Column(length: 255)]
    private string $email = '';

    #[ORM\Column(length: 32)]
    private string $phone = '';

    #[ORM\Column(type: "date_immutable", nullable: true)]
    private ?DateTimeImmutable $birthday = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTimeInterface $lastOrderedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBirthday(): ?DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(?DateTimeImmutable $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getLastOrderedAt(): ?DateTimeInterface
    {
        return $this->lastOrderedAt;
    }

    public function setLastOrderedAt(?DateTimeInterface $lastOrderedAt): self
    {
        $this->lastOrderedAt = $lastOrderedAt;

        return $this;
    }
}
