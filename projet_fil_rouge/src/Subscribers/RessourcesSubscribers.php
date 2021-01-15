<?php

namespace App\Subscribers;

use App\Entity\Ressources;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Photos;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RessourcesRepository;
use App\Repository\PhotosRepository;
use App\Repository\VideosRepository;
use App\Repository\ArticlesRepository;

class RessourcesSubscribers implements EventSubscriberInterface
{

	public function __construct(Security $security, RessourcesRepository $ressourcesRepository,EntityManagerInterface $entityManager, PhotosRepository $photosRepository, VideosRepository $videosRepository, ArticlesRepository $articlesRepository){
		$this->security = $security;
		$this->ressourcesRepository = $ressourcesRepository;
		$this->entityManager = $entityManager;
		$this->photosRepository = $photosRepository;
		$this->videosRepository = $videosRepository;
		$this->articlesRepository = $articlesRepository;
	}

	public static function getSubscribedEvents()
	{
		return[
			BeforeEntityPersistedEvent::class => ['setUser'],
			AfterEntityUpdatedEvent::class => ['updateElem'],
			BeforeEntityDeletedEvent::class => ['deleteElem']
		];

	}

	public function setUser(BeforeEntityPersistedEvent $event){

		$entity = $event->getEntityInstance();
			if ($entity instanceof Ressources){
				
				$entity->setUser($this->security->getUser());
				
			}
			
	}

	public function updateElem(AfterEntityUpdatedEvent $event){

		$entity = $event->getEntityInstance();
		//dd($entity);
			if ($entity instanceof Ressources){
				//dd($photo);
				if ($entity->getPhoto() != null){
					$entityManager = $this->entityManager;
					$entity->getPhoto()->setPublished($entity->getPublished());
					$entityManager->persist($entity->getPhoto());
            		$entityManager->flush();
				}

				if ($entity->getVideo() != null){
					$entityManager = $this->entityManager;
					$entity->getVideo()->setPublished($entity->getPublished());
					$entityManager->persist($entity->getVideo());
            		$entityManager->flush();
				}
				if ($entity->getEvenement() != null){
					$entityManager = $this->entityManager;
					$entity->getEvenement()->setPublished($entity->getPublished());
					$entityManager->persist($entity->getEvenement());
            		$entityManager->flush();
				}
				if ($entity->getArticle() != null){
					$entityManager = $this->entityManager;
					$entity->getArticle()->setPublished($entity->getPublished());
					$entityManager->persist($entity->getArticle());
            		$entityManager->flush();
				}
				if ($entity->getInformation() != null){
					$entityManager = $this->entityManager;
					$entity->getInformation()->setPublished($entity->getPublished());
					$entityManager->persist($entity->getInformation());
            		$entityManager->flush();
				}
            	
                
                
				
			}
			
	}

	public function deleteElem(BeforeEntityDeletedEvent $event){

		$entity = $event->getEntityInstance();
			if ($entity instanceof Ressources){

            if ($entity->getPhoto() != null){
            $photo = $this->ressourcesRepository->findOneBy(['photo' => $entity->getPhoto()]); 
            
                $entityManager = $this->entityManager;
                $entityManager->remove($photo);
            	$entityManager->flush();
            }

            if ($entity->getVideo() != null){ 
            $video = $this->ressourcesRepository->findOneBy(['video' => $entity->getVideo()]); 
         
                $entityManager = $this->entityManager;
                $entityManager->remove($video);
            	$entityManager->flush();
            }

            if ($entity->getEvenement() != null){
            $evenement = $this->ressourcesRepository->findOneBy(['evenement' => $entity->getEvenement()]);  

                $entityManager = $this->entityManager;
                $entityManager->remove($evenement);
            	$entityManager->flush();
            }

            if ($entity->getInformation() != null){
            $information = $this->ressourcesRepository->findOneBy(['information' => $entity->getInformation()]);   
                $entityManager = $this->entityManager;
                $entityManager->remove($information);
            	$entityManager->flush();
            }

            if ($entity->getArticle() != null){
            	// dd($this->ressourcesRepository->findOneBy(['article' => $entity->getArticle()]));

            $article = $this->ressourcesRepository->findOneBy(['article' => $entity->getArticle()]);   
                $entityManager = $this->entityManager;
                $entityManager->remove($article);
            	$entityManager->flush();
            }   
				
			}
			
	}
}

