<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    /**
     * Busca proyectos y carga sus empleados (eager loading)
     */
    public function findAllWithProjects() : array
    {
        return $this
               ->createQueryBuilder('e')
               ->leftJoin('e.projects', 'p')
               ->addSelect('p')
               ->getQuery()
               ->getResult();
    }

    public function findWithProject(int $id) : Project
    {
        return $this
                    ->createQueryBuilder('e')
                    ->leftJoin('e.projects', 'p')
                    ->addSelect('p')
                    ->where('e.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();

    }

}