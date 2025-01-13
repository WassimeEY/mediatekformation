<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private $hacheurDeMdp;
    
    public function __construct(UserPasswordHasherInterface $hacheurDeMdp) {
        $this->hacheurDeMdp = $hacheurDeMdp;
    }
    
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $mdpSansHachage = "admin";
        $mdpAvecHachage = $this->hacheurDeMdp->hashPassword($user, $mdpSansHachage);
        $user->setPassword($mdpAvecHachage);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }
}
