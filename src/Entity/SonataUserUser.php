<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

// or `Sonata\UserBundle\Entity\BaseUser3` as BaseUser if you upgrade to doctrine/orm ^3

enum GenderEnum: string
{
    case male = 'male';
    case female = 'female';
}

#[ORM\Entity]
#[ORM\Table(name: 'sonata_user')]
class SonataUserUser extends BaseUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(length: 255)]
    private ?string $full_name = null;

    #[ORM\Column(enumType: GenderEnum::class)]
    private ?GenderEnum $gender = null;

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(?string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getGender(): ?GenderEnum
    {
        return $this->gender;
    }

    public function setGender(?GenderEnum $gender): static
    {
        $this->gender = $gender;

        return $this;
    }
}
