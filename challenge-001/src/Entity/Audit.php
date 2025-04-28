<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Audit
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private  ? int $id;
    #[ORM\Column(type: 'string', length: 100)]
    private ? string $username;
    #[ORM\Column(type: 'string', length: 100)]
    private  ? string $action;
    #[ORM\Column(type: 'datetime')]
    private ? \DateTime $dateTime;
    #[ORM\Column(type: 'string', length: 100)]
    private  ? string $entityClass;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }


    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): void
    {
        $this->action = $action;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(?\DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    public function setEntityClass(?string $entityClass): void
    {
        $this->entityClass = $entityClass;
    }


}
