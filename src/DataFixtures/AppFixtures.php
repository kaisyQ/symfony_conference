<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Comment;
use App\Entity\Conference;
use App\Entity\Admin;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory
    ) {}
    public function load(ObjectManager $manager): void
    {

        $moscow = new Conference();
        $moscow->setCity('Moscow');
        $moscow->setIsInternational(true);
        $moscow->setYear(2025);

        $manager->persist($moscow);


        $paris = new Conference();
        $paris->setCity('Paris');
        $paris->setIsInternational(true);
        $paris->setYear(2026);


        $manager->persist($paris);


        $comment = new Comment();
        $comment->setEmail('comment@gmail.com');
        $comment->setAuthor('Toby Cracket');
        $comment->setConference($paris);
        $comment->setState('published');
        $comment->setText("that was really cool. i learn a lot of new");
        
        $manager->persist($comment);

        
        $admin = new Admin();   
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('boss');
        $admin->setPassword($this->passwordHasherFactory->getPasswordHasher(Admin::class)->hash('admin'));
        
        
        $manager->persist($admin);



        $manager->flush();
    }
}
