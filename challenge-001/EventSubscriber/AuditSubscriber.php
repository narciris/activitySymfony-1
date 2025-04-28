<?php

namespace EventSubscriber;

use App\Service\AuditService;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AuditSubscriber implements EventSubscriberInterface
{

    private $auditService;

    public function __construct(AuditService $auditService)
    {
      $this->auditService = $auditService;
    }

    private array $auditableEntities = [
        'App\Entity\Project',
        'App\Entity\Employee',
    ];

    public static function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('CREATE', $args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->logActivity('UPDATE', $args);
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $this->logActivity('DELETE', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $className = get_class($entity);

        if (!in_array($className, $this->auditableEntities)) {
            return;
        }

        $this->auditService->log($entity,$action);


    }
}