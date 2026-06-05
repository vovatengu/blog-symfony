<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Entity\BaseGalleryItem;

#[ORM\Table(name: 'media__gallery_item')]
#[ORM\Entity]
class SonataMediaGalleryItem extends BaseGalleryItem
{
    use Traits\IdTrait;
}
