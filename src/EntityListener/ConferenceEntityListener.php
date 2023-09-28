<?php

namespace App\EntityListener;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;


#[AsEntityListener(event: Events::prePersist, entity:Conference::class)]

#[AsEntityListener(event: Events::preUpdate, entity:Conference::class)]
class ConferenceEntityListener {
    public function __construct
    (
        private SluggerInterface $sluggerInterface,
    ) {}

    public function prePersist (Conference $conference, LifecycleEventArgs $lifecycleEventArgs) {
        $conference->computeSlug($this->sluggerInterface);
    }

    public function preUpdate(Conference $conference, LifecycleEventArgs $lifecycleEventArgs) {
        $conference->computeSlug($this->sluggerInterface);
    }
}