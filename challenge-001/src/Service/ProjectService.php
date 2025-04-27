<?php

namespace App\Service;

use App\Dtos\ProjectRequestDto;
use App\Dtos\ProjectResponseDto;
use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Exception;

class ProjectService
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
         $this->entityManager = $entityManager;
    }

    public function getAllProjects() : array
    {
        try {

            $projects = $this->entityManager->getRepository(Project::class)->findAll();
            return  $projects;
        }catch (\Exception $e){
            throw new Exception("Error al recuperar proyectos", $e->getCode(), $e->getMessage());
        }
    }

    public function getProjectById(int $id): ?ProjectResponseDto

    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);
        if (!$project) {
            throw new Exception("projecto no encontrado");
        }
        return $this->mapToProjectResponse($project);
    }

    private function mapToProjectResponse(Project $project): ProjectResponseDto
    {
        $projectDto = new PRojectResponseDto();
        $projectDto->setName($project->getTitle());
        $projectDto->setId($project->getId());
        $projectDto->setStartDate($project->getStartDate());
        $projectDto->setEndDate($project->getEndDate());

        return $projectDto;

    }

    public function createProject(ProjectRequestDto $projectDto): ProjectResponseDto
    {
        $this->validate($projectDto);

        if($projectDto->getStartDate() > $projectDto->getEndDate()) {
            throw new Exception("fecha de inicio no puede ser mayor que la fecha de fin");
        }

        $existingProject = $this->entityManager->getRepository(Project::class)
            ->findOneBy(['title' => trim($projectDto->getTitle())]);

        if ($existingProject) {
            throw new \Exception("El título del proyecto ya existe.");
        }


        $project = new Project();
        $project->setTitle($projectDto->getTitle());
        $project->setStartDate($projectDto->getStartDate());
        $project->setEndDate($projectDto->getEndDate());

        $this->addEmployees($projectDto,$project);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $this->mapToProjectResponse($project);

    }

    public function updateProject(int $id, ProjectRequestDto $requestDto): ProjectResponseDto
    {
        $findProject = $this->entityManager->getRepository(Project::class)->find($id);
        if(!$findProject) {
            throw new Exception("proyecto no encontrado");
        }
        if($requestDto->getTitle() !== null && $requestDto->getTitle() !== $findProject->getTitle()) {
            $findProject->setTitle($requestDto->getTitle());
        }
        if($requestDto->getStartDate() !== null && $requestDto->getStartDate() !== $findProject->getStartDate()) {
            $findProject->setStartDate($requestDto->getStartDate());

        }
        if($requestDto->getEndDate() !== null && $requestDto->getEndDate() !== $findProject->getEndDate()) {
            $findProject->setEndDate($requestDto->getEndDate());
        }
//        $this->addEmployees($requestDto,$findProject);


        $this->entityManager->flush();
        return $this->mapToProjectResponse($findProject);

    }

    private function validate(ProjectRequestDto $requestDto) : void
    {
        if (empty(trim($requestDto->getTitle())) ||
            empty($requestDto->getStartDate()) ||
            empty($requestDto->getEndDate())) {
            throw new \Exception("Todos los campos son obligatorios.");
        }

    }

    private function addEmployees(ProjectRequestDto $requestDto,Project $project)
    {
        if(!empty($requestDto->getEmployeesId())){
            $employeeRepository = $this->entityManager->getRepository(Employee::class);
            foreach ($requestDto->getEmployeesId() as $employeeId) {
                $employee = $employeeRepository->find($employeeId);
                if($employee){
                    $project->setEmployees($employee);
                }
                else{
                    throw new Exception("El usuario no existe");
                }
            }

        }
    }

public function deleteProject(int $id)
{
    $project = $this->entityManager->getRepository(Project::class)->find($id);
    if(!$project){
        throw new Exception("proyecto no encontrado");
    }

    try {
        $this->entityManager->remove($project);
        $this->entityManager->flush();

    }catch (\Exception $e){
        throw new Exception("Error al eliminar el proyecto");
    }
}

}