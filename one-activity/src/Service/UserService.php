<?php

declare(strict_types=1);

namespace App\Service;

use App\Dtos\UserDto;
use App\Dtos\UserRequestDto;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private EntityManagerInterface $entityManager;
     public function __construct(EntityManagerInterface $entityManager)
     {
         $this->entityManager = $entityManager;
     }

     public function findAll() : array
     {
         $users = $this->entityManager->getRepository(Users::class)->findAll();
         error_log('Total users: ' . count($users));
         return array_map([$this, 'mapToUserDto'], $users);
     }

     private function mapToUserDto(Users $user) : UserDto
     {
         error_log("ID: " . $user->getId());
         error_log("Name: " . $user->getName());

         $dto= new UserDto();
         $dto->setId($user->getId());
         $dto->setName($user->getName());
         $dto->setLastname($user->getLastname());
         $dto->setDocument($user->getDocument());
         $dto->setImgUrl($user->getImgUrl());
         return $dto;

     }

     public function createUsers(UserRequestDto $requestDto) : UserDto
     {
         $create = new Users();
         $create->setName($requestDto->name);
         $create->setLastname($requestDto->lastname);
         $create->setDocument($requestDto->document);
         $create->setImgUrl($requestDto->imgUrl);

         $this->entityManager->persist($create);
         $this->entityManager->flush();
         return  $this->mapToUserDto($create);

     }

     public function findById($id) : UserDto
     {
         $users = $this->entityManager->getRepository(Users::class)->find($id);
         if(!$users){
             throw new \Exception("usuario no encontrado",404);
         }

         return $this->mapToUserDto($users);
     }
}