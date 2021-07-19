<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spipu\UserBundle\Entity\AbstractUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass="Spipu\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="spipu_user")
 */
class User extends AbstractUser implements PasswordAuthenticatedUserInterface
{
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $middleName;

    /**
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * @param string|null $middleName
     * @return self
     */
    public function setMiddleName(?string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }
}
