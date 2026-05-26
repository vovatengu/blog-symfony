<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

// or `Sonata\UserBundle\Entity\BaseUser3` as BaseUser if you upgrade to doctrine/orm ^3

#[ORM\Entity]
#[ORM\Table(name: 'app__user')]
class SonataUserUser extends BaseUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $full_name = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $gender = null;

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(?string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }
}
