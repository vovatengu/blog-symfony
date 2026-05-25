<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

// or `Sonata\UserBundle\Entity\BaseUser3` as BaseUser if you upgrade to doctrine/orm ^3

#[ORM\Entity]
#[ORM\Table(name: 'user__user')]
class SonataUserUser extends BaseUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;
}
