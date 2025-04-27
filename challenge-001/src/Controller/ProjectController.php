<?php

namespace App\Controller;

use App\Dtos\ProjectRequestDto;
use App\Service\ProjectService;
use DateTime;
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
                $this->addFlash('error',
                    'Debes iniciar sesión para acceder a esta página');
                return $this->redirectToRoute('app_login');
            }

            $projects = $this->projecService->getAllProjects();

            return $this->render('project/home.html.twig',
                ['projects' => $projects]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error al cargar proyectos: ' . $e->getMessage());

            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }

            return $this->render('error/general.html.twig', [
                'message' => $e->getMessage()
            ]);
    }


    }
    #[Route('/findById/{id}', name: 'project_show')]

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
    public function store(Request $request): Response
    {
        $projectDto = new ProjectRequestDto();
        $projectDto->setTitle($request->get('title'));
        $projectDto->setStartDate(new DateTime($request->get('startDate')));
        $projectDto->setEndDate(new DateTime($request->get('endDate')));
        $employeesString = $request->get('employees', '');
        $employeesArray = array_map('trim', explode(',', $employeesString));
        $projectDto->setEmployeesId($employeesArray);



        try {
           $this->projecService->createProject($projectDto);
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
    #[Route('/edit/{id}', name: 'project_edit', methods: ['GET'])]
    public function edit(int $id)

    {
        $project = $this->projecService->getProjectById($id);
        return $this->render(
            'project/edit.html.twig',
            ['project'=>$project]);
    }
    #[Route('/update/{id}', name: 'project_update', methods: ['POST'])]
    public function update(int $id, Request $request) : Response
    {
        $projectDto = new ProjectRequestDto();
        $projectDto->setTitle($request->get('title'));
        $projectDto->setStartDate(new DateTime($request->get('startDate')));
        $projectDto->setEndDate(new DateTime($request->get('endDate')));
//        $employeesString = $request->get('employees', '');
//        $employeesArray = array_map('trim', explode(',', $employeesString));
//        $projectDto->setEmployeesId($employeesArray);

        try {
            $this->projecService->updateProject($id, $projectDto);
            return $this->redirectToRoute('project_home');
        }catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return  $this->redirectToRoute('project_edit',['id'=>$id]);
        }
    }

    #[Route('/delete/{id}', name: 'project_delete', methods: ['POST'])]

    public function delete(int $id) : Response
    {
        try{
            $this->projecService->deleteProject($id);
            $this->addFlash('Success','proyecto eliminado correctamente');
            return  $this->redirectToRoute('project_home');
        }catch (\Exception $e){
            $this->addFlash('error', 'Hubo un error al eliminar el proyecto: ' . $e->getMessage());
           return   $this->redirectToRoute('project_home');
        }

    }


}