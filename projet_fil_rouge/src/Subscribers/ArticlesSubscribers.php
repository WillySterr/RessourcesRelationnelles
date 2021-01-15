<?php

namespace App\Subscribers;

use App\Entity\Articles;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Ressources;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RessourcesRepository;


class ArticlesSubscribers implements EventSubscriberInterface
{


	public function __construct(Security $security,EntityManagerInterface $entityManager, RessourcesRepository $ressourcesRepository){
		$this->security = $security;
		$this->entityManager = $entityManager;
		$this->ressourcesRepository = $ressourcesRepository;

	}

	public static function getSubscribedEvents()
	{
		return[
			BeforeEntityPersistedEvent::class => ['setUserAndRessource'],
			BeforeEntityUpdatedEvent::class => ['updateUserAndRessource'],
			BeforeEntityDeletedEvent::class => ['deleteRessource'],
		
		];

	}

	public function setUserAndRessource(BeforeEntityPersistedEvent $event){

		$entity = $event->getEntityInstance();
			if ($entity instanceof Articles){
				
				$entity->setUser($this->security->getUser());

				$ressource = new Ressources();
            	$ressource->setUser($this->security->getUser())
                	->setArticle($entity)             
                	->setTitle($entity->getTitre());
                
                $ressource->setPublished($entity->getPublished());
                
                $entityManager = $this->entityManager;
                $entityManager->persist($ressource);
            	$entityManager->flush();
                
				
			}
			
	}

	public function updateUserAndRessource(BeforeEntityUpdatedEvent $event){

		$entity = $event->getEntityInstance();
			if ($entity instanceof Articles){

				$ressource = $this->ressourcesRepository->findOneBy(["article" => $entity->getId()]);
			
            	$ressource->setUser($this->security->getUser())
            		->setArticle($entity)             
                	->setTitle($entity->getTitre());
                
                $ressource->setPublished($entity->getPublished());
                
                $entityManager = $this->entityManager;
                $entityManager->persist($ressource);
            	$entityManager->flush();
                
				
			}
			
	}


	public function deleteRessource(BeforeEntityDeletedEvent $event){

		$entity = $event->getEntityInstance();
			if ($entity instanceof Articles){

				$ressource = $this->ressourcesRepository->findOneBy(["article" => $entity->getId()]);
                
                $entityManager = $this->entityManager;
                $entityManager->remove($ressource);
            	$entityManager->flush();
                
				
			}
			
	}


}
			