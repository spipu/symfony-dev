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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Spipu\UiBundle\Entity\EntityInterface;
use Spipu\UiBundle\Entity\TimestampableInterface;
use Spipu\UiBundle\Entity\TimestampableTrait;

#[ORM\Entity(repositoryClass: 'App\Repository\DemoOrderRepository')]
#[ORM\Table(name: "demo_order")]
#[ORM\HasLifecycleCallbacks]
class DemoOrder implements EntityInterface, TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: DemoCustomer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?DemoCustomer $customer = null;

    #[ORM\Column(length: 32)]
    private string $reference = '';

    #[ORM\Column(length: 32)]
    private string $status = '';

    #[ORM\Column(type: "float")]
    private float $amount = 0.;

    #[ORM\Column(length: 255)]
    private string $customerFirstName = '';

    #[ORM\Column(length: 255)]
    private string $customerLastName = '';

    #[ORM\Column(length: 255)]
    private string $customerEmail = '';

    /**
     * @var Collection<int, DemoOrderAddress>
     */
    #[ORM\OneToMany(targetEntity: DemoOrderAddress::class, mappedBy: "order", cascade: ["persist", "remove"])]
    #[ORM\OrderBy(["type" => "DESC"])]
    private Collection $addresses;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCustomerFirstName(): string
    {
        return $this->customerFirstName;
    }

    public function setCustomerFirstName(string $customerFirstName): self
    {
        $this->customerFirstName = $customerFirstName;

        return $this;
    }

    public function getCustomerLastName(): string
    {
        return $this->customerLastName;
    }

    public function setCustomerLastName(string $customerLastName): self
    {
        $this->customerLastName = $customerLastName;

        return $this;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * @return Collection<int, DemoOrderAddress>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function getAddress(string $type): ?DemoOrderAddress
    {
        foreach ($this->addresses as $address) {
            if ($address->getType() === $type) {
                return $address;
            }
        }

        return null;
    }

    public function addAddress(DemoOrderAddress $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setOrder($this);
        }

        return $this;
    }
}
