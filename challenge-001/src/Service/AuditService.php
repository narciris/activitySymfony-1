<?php

namespace App\Service;

use App\Entity\Audit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AuditService
{
    private $entityManager;
    private $security;
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function log($entity,$action)
    {
        $user = $this->security->getUser();
        $username =  $user ? $user->getUserIdentifier() : 'anonymous';

        $audit = new Audit();
        $audit->setUsername($username);
        $audit->setAction($action);
        $audit->setEntityClass(get_class($entity));
        $audit->setDateTime(new \DateTime);
        $this->entityManager->persist($audit);
        $this->entityManager->flush();

    }


}