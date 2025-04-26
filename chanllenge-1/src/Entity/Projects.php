<?php

namespace App\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private  ? int $id;
    #[ORM\Column(type: 'string', length: 100)]

    private ? string $title;
    #[ORM\Column(type: 'string', length: 100)]

    private  ? string $startDate;
    #[ORM\Column(type: 'string', length: 100)]

    private ? string $endDate;
    #[ORM\OneToMany(targetEntity: Projects::class, mappedBy: 'employee')]
    private ? Collection $employees;
}