<?php

namespace App\Subscribers;

use App\Entity\Ressources;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class RessourcesSubscribers implements EventSubscriberInterface
{

	public function __construct(Security $security){
		$this->security = $security;
	}

	public static function getSubscribedEvents()
	{
		return[
			BeforeEntityPersistedEvent::class => ['setUser']
		];

	}

	public function setUser(BeforeEntityPersistedEvent $event){

		$entity = $event->getEntityInstance();
			if ($entity instanceof Ressources){
				
				$entity->setUser($this->security->getUser());
				
			}
			
	}
}

