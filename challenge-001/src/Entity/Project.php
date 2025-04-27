<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private  ? int $id;
    #[ORM\Column(type: 'string', length: 100)]

    private ? string $title;
    #[ORM\Column(type: 'datetime')]
    private  ? \DateTime $startDate;
    #[ORM\Column(type: 'datetime')]
    private ? \DateTime $endDate;
    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'employee')]
    #[ORM\JoinTable(name: 'project_employee')]
    private ? Collection $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getEmployees(): ?Collection
    {
        return $this->employees;
    }

    public function setEmployees(?Collection $employees): void
    {
        $this->employees = $employees;
    }


}