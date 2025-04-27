<?php

namespace App\Controller;

use App\Dtos\ProjectRequestDto;
use App\Service\ProjectService;
use PharIo\Manifest\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/projects')]
class ProjectController extends AbstractController
{
    private $projecService;

    public function __construct(ProjectService $projectService)
    {
        $this->projecService = $projectService;
    }
    #[Route('/home', name: 'project_home')]
    public function index(): Response
    {
        try {
            $user = $this->getUser();

            if (!$user) {
                return $this->redirectToRoute('app_login');
            }

            $projects = $this->projecService->getAllProjects();

            return $this->render('project/home.html.twig',
                ['projects' => $projects]);
        }catch (\Exception $e){
            return $this->redirectToRoute('app_login');
        }

    }
    #[Route('/findById/{id}', name: 'project_findId')]

    public function show(int $id) : Response
    {
        try{
            $project = $this->projecService->getProjectById($id);
            return $this->render('project/show.html.twig',['project' => $project]);

        }catch (Exception $e){
            throw new \Exception("error",$e->getCode(),$e->getMessage());
        }
    }
    #[Route('/store', name: 'project_store', methods: ['POST'])]
    public function store(Request $request, ProjectService $projectService): Response
    {
        $projectDto = new ProjectRequestDto();
        $projectDto->setTitle($request->get('title'));
        $projectDto->setStartDate($request->get('startDate'));
        $projectDto->setEndDate($request->get('endDate'));

        try {
          $create=  $this->projecService->createProject($projectDto);
          return  $this->redirectToRoute('project_home');
        }catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('project_create');
        }

    }
    #[Route('/create', name: 'project_create', methods: ['GET'])]
    public function create() : Response
    {
        return $this->render('project/create.html.twig');

    }


}