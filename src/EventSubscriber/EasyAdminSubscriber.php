<?php

namespace App\EventSubscriber;

use App\Entity\Etablissement;
use App\Entity\News;
use App\Entity\User;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
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
    private $etablissementRepository;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasherInterface,
        EntityManagerInterface $em,
        EtablissementRepository $etablissementRepository
    ) {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->em = $em;
        $this->etablissementRepository = $etablissementRepository;
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
        $this->putTagsAndSecteursForSearchEtablissement($event);
    }

    public function beforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event): void
    {
        $this->updateUserPassword($event);
        $this->putTagsAndSecteursForSearchEtablissement($event);
    }

    public function afterEntityPersistedEvent(AfterEntityPersistedEvent $event)
    {
        $this->assignNewsAndDateLimiteInscriptionToEtablissement($event);
    }

    public function afterEntityUpdatedEvent(AfterEntityUpdatedEvent $event)
    {
        $this->putNewsAndDateLimiteInscriptionInEtablissement($event);
    }

    public function updateUserPassword($event)
    {
        $entityInstance = $event->getEntityInstance();

        if ($entityInstance instanceof User && $entityInstance->getPlainPassword()) {
            $hashedPassword = $this->userPasswordHasherInterface->hashPassword($entityInstance, $entityInstance->getPlainPassword());

            $entityInstance->setPassword($hashedPassword);
        }
    }

    public function assignNewsAndDateLimiteInscriptionToEtablissement($event)
    {
        $entityInstance = $event->getEntityInstance();

        if ($entityInstance instanceof News && $entityInstance->getEtablissement() && $entityInstance->getDateLimitInscription()) {
            $etablissement = $entityInstance->getEtablissement();
            $etablissement->setNews($entityInstance);
            $this->em->persist($etablissement);
            $this->em->flush();
        }
    }

    public function putNewsAndDateLimiteInscriptionInEtablissement($event)
    {
        $entityInstance = $event->getEntityInstance();
        if ($entityInstance instanceof News && $entityInstance->getEtablissement() && $entityInstance->getDateLimitInscription()) {
            $old_etablissement = $this->etablissementRepository->findOneByNews($entityInstance->getId());
            $new_etablissement = $entityInstance->getEtablissement();

            if ($old_etablissement->getId() !== $new_etablissement->getId()) {
                $old_etablissement->setNews(null);
                $new_etablissement->setNews($entityInstance);
                $this->em->persist($old_etablissement);
                $this->em->persist($new_etablissement);
                $this->em->flush();
            }
        }
    }

    public function putTagsAndSecteursForSearchEtablissement($event)
    {
        $entityInstance = $event->getEntityInstance();

        if ($entityInstance instanceof Etablissement) {
            $secteurs = $entityInstance->getSecteurs();
            $tags = $entityInstance->getTags();
            $villes = $entityInstance->getVilles();


            if ($secteurs->count()) {
                $secteursText = "";
                foreach ($secteurs as $secteur) {
                    $secteursText .=  $secteur->getName() . ', ';
                }
                $entityInstance->setSecteursText($secteursText);
            }

            if ($tags->count()) {
                $tagsText = "";
                foreach ($tags as $tag) {
                    $tagsText .=  $tag->getName() . ', ';
                }
                $entityInstance->setTagsText($tagsText);
            }

            if ($villes->count()) {
                $villesText = "";
                foreach ($villes as $ville) {
                    $villesText .=  $ville->getName() . ', ';
                }
                $entityInstance->setVillesText($villesText);
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'beforeEntityPersistedEvent',
            BeforeEntityUpdatedEvent::class => 'beforeEntityUpdatedEvent',
            AfterEntityPersistedEvent::class => 'afterEntityPersistedEvent',
            AfterEntityUpdatedEvent::class => 'afterEntityUpdatedEvent',
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
