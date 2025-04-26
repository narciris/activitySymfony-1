<?php

namespace App\Entity;

use phpDocumentor\Reflection\Types\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
class Employee
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ? int $id;
    #[ORM\Column(type: 'string', length: 100)]
    private ? string $name;
    #[ORM\Column(type: 'string', length: 100,unique: true)]
    private ? string $email;
    #[ORM\Column(type: 'string', length: 100)]
    private ? string $position;
    #[ORM\OneToMany(targetEntity: Projects::class, mappedBy: 'employee')]
    private ? Collection $projects;


}