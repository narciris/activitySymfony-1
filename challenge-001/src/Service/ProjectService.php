<?php

namespace App\Service;

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


}