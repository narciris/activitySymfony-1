<?php

namespace App\EventSubscriber;

use App\Service\AuditService;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuditSubscriber implements EventSubscriberInterface
{

    private AuditService $auditService;
    private $logger;


    public function __construct(AuditService $auditService, LoggerInterface $logger)
    {
      $this->auditService = $auditService;
      $this->logger = $logger;
    }

    private array $auditableEntities = [
        'App\Entity\Project',
        'App\Entity\Employee',
        'App\Entity\User',
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
        $this->logger->info('postPersist triggered');
        $this->logActivity('CREATE', $args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->logger->info('postUpdate triggered');
        $this->logActivity('UPDATE', $args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->logger->info('postRemove triggered for ' . get_class($args->getObject()));
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