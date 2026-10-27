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
use Spipu\UiBundle\Entity\EntityInterface;
use Spipu\UiBundle\Entity\TimestampableInterface;
use Spipu\UiBundle\Entity\TimestampableTrait;

#[ORM\Entity(repositoryClass: 'App\Repository\DemoOrderAddressRepository')]
#[ORM\Table(name: "demo_order_address")]
#[ORM\UniqueConstraint(name: "UNIQ_DEMO_ORDER_ADDRESS_TYPE", columns: ["order_id", "type"])]
#[ORM\HasLifecycleCallbacks]
class DemoOrderAddress implements EntityInterface, TimestampableInterface
{
    use TimestampableTrait;

    public const TYPE_SHIPPING = 'shipping';
    public const TYPE_BILLING = 'billing';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: DemoOrder::class, inversedBy: "addresses")]
    #[ORM\JoinColumn(name: "order_id", nullable: false, onDelete: "CASCADE")]
    private ?DemoOrder $order = null;

    #[ORM\Column(length: 16)]
    private string $type = '';

    #[ORM\Column(length: 255)]
    private string $firstname = '';

    #[ORM\Column(length: 255)]
    private string $lastname = '';

    #[ORM\Column(length: 32)]
    private string $phone = '';

    #[ORM\Column(length: 255)]
    private string $email = '';

    #[ORM\Column(length: 255)]
    private string $address = '';

    #[ORM\Column(length: 64)]
    private string $city = '';

    #[ORM\Column(length: 10)]
    private string $postalCode = '';

    #[ORM\Column(length: 32)]
    private string $country = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?DemoOrder
    {
        return $this->order;
    }

    public function setOrder(DemoOrder $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
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

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }
}
