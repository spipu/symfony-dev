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

use App\Repository\DemoOrderRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Spipu\UiBundle\Entity\EntityInterface;
use Spipu\UiBundle\Entity\PersonalDataProtectedInterface;

#[ORM\Entity(repositoryClass: DemoOrderRepository::class)]
#[ORM\Table(name: "demo_order")]
class DemoOrder implements EntityInterface, PersonalDataProtectedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private string $reference = '';

    #[ORM\Column(length: 32)]
    private string $status = '';

    #[ORM\Column(type: "float")]
    private float $amount = 0.;

    #[ORM\Column(length: 128)]
    private string $city = '';

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?DateTimeImmutable $personalDataHiddenAt = null;

    #[ORM\ManyToOne(targetEntity: DemoCustomer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?DemoCustomer $customer = null;

    public static function getPersonalDataFields(): array
    {
        return ['customer.email', 'customer.name', 'customer.phone'];
    }

    public static function getPersonalDataHiddenAtField(): string
    {
        return 'personalDataHiddenAt';
    }

    public function isPersonalDataHidden(): bool
    {
        return (($this->personalDataHiddenAt !== null) && ($this->personalDataHiddenAt <= new DateTimeImmutable()));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPersonalDataHiddenAt(): ?DateTimeImmutable
    {
        return $this->personalDataHiddenAt;
    }

    public function setPersonalDataHiddenAt(?DateTimeImmutable $personalDataHiddenAt): self
    {
        $this->personalDataHiddenAt = $personalDataHiddenAt;

        return $this;
    }

    public function getCustomer(): ?DemoCustomer
    {
        return $this->customer;
    }

    public function setCustomer(DemoCustomer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
