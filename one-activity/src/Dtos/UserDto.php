<?php
declare(strict_types=1);

namespace App\Dtos;

class UserDto  implements \JsonSerializable
{

    private int $id;
    private string $name;
    private string $lastname;
    private string $document;
    private string $imgUrl;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function setDocument(string $document): void
    {
        $this->document = $document;
    }

    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }


    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'document' => $this->document,
            'imgUrl' => $this->imgUrl,
        ];
    }
}