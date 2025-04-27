<?php

namespace App\Service;

use App\Dtos\ProjectRequestDto;
use App\Dtos\ProjectResponseDto;
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
            throw new Exception("Error al recuperar proyectos");
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

        return $projectDto;

    }

    public function createProject(ProjectRequestDto $projectDto): ProjectResponseDto
    {
        if (empty(trim($projectDto->getTitle())) ||
            empty($projectDto->getStartDate()) ||
            empty($projectDto->getEndDate())) {
            throw new \Exception("Todos los campos son obligatorios.");
        }
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
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $this->mapToProjectResponse($project);

    }

}