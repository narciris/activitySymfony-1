<?php

namespace App\Entity;
use Doctrine\Common\Collections\Collection;
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
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'employee')]
    private ? Collection $projects;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    public function getProjects(): ?Collection
    {
        return $this->projects;
    }

    public function setProjects(?Collection $projects): void
    {
        $this->projects = $projects;
    }



}