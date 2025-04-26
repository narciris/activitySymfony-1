<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
class Audit
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private  ? int $id;
    #[ORM\Column(type: 'string', length: 100)]

    private ? string $user;
    #[ORM\Column(type: 'string', length: 100)]

    private  ? string $action;
    #[ORM\Column(type: 'string', length: 100)]

    private ? DateTimeInterface $dateTime;

    private  ? string $entityClass;

}