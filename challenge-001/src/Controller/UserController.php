<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
private UserPasswordHasherInterface $passwordHashed;
private EntityManagerInterface $entityManager;
    public function __construct(UserPasswordHasherInterface $passwordHashed,
                                EntityManagerInterface $entityManager)
    {
      $this->passwordHashed = $passwordHashed;
      $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'user_register')]
    public function register() : Response
    {
        try {


            $user = new User();
            $user->setEmail('test@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHashed->hashPassword($user, '1234'));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new Response("User ID: " . $user->getId());
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }
    }


}