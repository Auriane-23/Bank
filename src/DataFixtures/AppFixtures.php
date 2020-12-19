<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstname('Auriane');
        $user->setLastname('Couteleau');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('couteleauauriane@gmail.com');
        $user->setBirthday(new \DateTime('2000-06-23'));

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123'
        ));

        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setFirstname('Auriane');
        $user->setLastname('Couteleau');
        $user->setRoles(['ROLE_USER']);
        $user->setEmail('auriane.couteleau@lesforgesduweb.fr');
        $user->setBirthday(new \DateTime('2000-06-23'));

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123'
        ));

        $manager->persist($user);
        $manager->flush();
    }
}