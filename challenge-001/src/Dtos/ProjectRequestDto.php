<?php

namespace App\Dtos;

class ProjectRequestDto
{

    private ? string $title;
    private  ? \DateTime $startDate;
    private ? \DateTime $endDate;
   private array $employeesId = [];

    public function getEmployeesId(): array
    {
        return $this->employeesId;
    }

    public function setEmployeesId(array $employeesId): void
    {
        $this->employeesId = $employeesId;
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



}