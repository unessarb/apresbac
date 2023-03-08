<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $userPasswordHasherInterface;
    private $em;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasherInterface,
        EntityManagerInterface $em
    ) {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->em = $em;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ('app_admin' === $event->getRequest()->attributes->get('_route')) {
            $this->em->getFilters()->disable('published_filter');
        }
    }

    public function beforeEntityPersistedEvent(BeforeEntityPersistedEvent $event): void
    {
        $this->updateUserPassword($event);
    }

    public function beforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event): void
    {
        $this->updateUserPassword($event);
    }

    public function updateUserPassword($event)
    {
        $entityInstance = $event->getEntityInstance();

        if ($entityInstance instanceof User && $entityInstance->getPlainPassword()) {
            $hashedPassword = $this->userPasswordHasherInterface->hashPassword($entityInstance, $entityInstance->getPlainPassword());

            $entityInstance->setPassword($hashedPassword);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'beforeEntityPersistedEvent',
            BeforeEntityUpdatedEvent::class => 'updateUserPassword',
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
