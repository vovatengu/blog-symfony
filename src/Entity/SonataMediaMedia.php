<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Entity\BaseMedia;

#[ORM\Table(name: 'media__media')]
#[ORM\Entity]
class SonataMediaMedia extends BaseMedia
{
    use Traits\IdTrait;
}
