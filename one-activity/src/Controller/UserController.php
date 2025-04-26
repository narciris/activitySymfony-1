<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dtos\UserRequestDto;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/v1')]
class UserController extends AbstractController
{


    public function __construct(private UserService $userService)

    {

    }
    #[Route('/users', name:  'get_all_users', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $users = $this->userService->findAll();
        return $this->json($users);
    }

   #[Route('/create', name:  'create_user', methods: ['POST'])]
    public function create(Request $requestDto):JsonResponse
    {
        $data = json_decode($requestDto->getContent(), true);

        if(!isset($data['name'], $data['lastname'], $data['document'], $data['imgUrl'])) {
            return $this->json(['message'=> 'campos faltantes'],Response::HTTP_BAD_REQUEST);
        }

        $requestD = new UserRequestDto(
            $data['name'],
        $data['lastname'],
        $data['document'],
        $data['imgUrl']
        );

        $save = $this->userService->createUsers($requestD);
        return $this->json($save, Response::HTTP_CREATED);

    }

    #[Route('/find/{id}',name:  'find', methods: ['GET'])]
    public function findByUserId($id): JsonResponse
    {
        try {
            $user = $this->userService->findById($id);
            return $this->json($user);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}