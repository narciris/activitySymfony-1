<?php

namespace App\Dtos;

class UserRequestDto
{
    public string $name;
    public string $lastname;
    public string $document;
    public string $imgUrl;

    /**
     * @param string $name
     * @param string $lastname
     * @param string $document
     * @param string $imgUrl
     */
    public function __construct(string $name, string $lastname, string $document, string $imgUrl)
    {
        $this->name = $name;
        $this->lastname = $lastname;
        $this->document = $document;
        $this->imgUrl = $imgUrl;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function setDocument(string $document): void
    {
        $this->document = $document;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }



}