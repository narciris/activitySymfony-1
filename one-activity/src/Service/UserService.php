<?php

declare(strict_types=1);

namespace App\Service;

use App\Dtos\UserDto;
use App\Dtos\UserRequestDto;
use App\Entity\Users;
use Doctrine\DBAL\Driver\PDO\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserService
{
    private EntityManagerInterface $entityManager;
    private $uploadDirectory;
     public function __construct(EntityManagerInterface $entityManager, $uploadDirectory)
     {
         $this->entityManager = $entityManager;
         $this->uploadDirectory = $uploadDirectory;
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


     public function uploadFile(int $user,UploadedFile $uploadedFile)
     {
         if(!$uploadedFile){
             throw new Exception("no se encontro ningun archivo");
         }

         $user = $this->entityManager->getRepository(Users::class)->find($user);
         if(!$user){
             throw new Exception("usuario no encontrado");
         }
         $filename = uniqid() . '.' . $uploadedFile->guessExtension();
         if (!in_array($uploadedFile->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
             throw new Exception('Solo puedes subir imagenes.');
         }
         $uploadedFile->move($this->uploadDirectory, $filename);


         $user->setImgUrl('/uploads/profile_images/' . $filename);
         $this->entityManager->flush();

         return $user->getImgUrl();

     }
}