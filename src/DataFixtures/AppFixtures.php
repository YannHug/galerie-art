<?php

namespace App\DataFixtures;

use App\Entity\Blogpost;
use App\Entity\Categorie;
use App\Entity\Peinture;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // USER
        $user = new User();
        $user->setEmail('user@test.com')
            ->SetPrenom($faker->firstName())
            ->setNom($faker->lastName())
            ->setTelephone($faker->phoneNumber())
            ->setAPropos($faker->text())
            ->setInstagram('instagram')
            ->setRoles(['ROLE_PEINTRE']);

        $password = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($password);

        $manager->persist($user);

        // Blogpost
        for ($i = 0; $i < 10; $i++) {
            $blogpost = new Blogpost();

            $blogpost->setTitre($faker->words(3, true))
                ->setCreatedAt($faker->dateTimeBetween('-6 month', 'now'))
                ->setContenu($faker->text(350))
                ->setSlug($faker->slug(3))
                ->setUser($user);

            $manager->persist($blogpost);
        }

        // Categorie
        for ($k = 0; $k < 5; $k++) {
            $categorie = new Categorie();

            $categorie->setNom($faker->word())
                ->setDescription($faker->words(10, true))
                ->setSlug($faker->slug(3));

            $manager->persist($categorie);

            // Categorie
            for ($j = 0; $j < 2; $j++) {
                $peinture = new Peinture();

                $peinture->setNom($faker->words(3, true))
                    ->setLargeur($faker->randomFloat(2, 20, 60))
                    ->setHauteur($faker->randomFloat(2, 20, 60))
                    ->setEnVente($faker->randomElement([true, false]))
                    ->setDateRealisation($faker->dateTimeBetween('-6 month', 'now'))
                    ->setCreatedAt($faker->dateTimeBetween('-6 month', 'now'))
                    ->setDescription($faker->text())
                    ->setPortfolio($faker->randomElement([true, false]))
                    ->setSlug($faker->slug(3))
                    ->setFile('./assets/images/placeholder.jpg')
                    ->addCategorie($categorie)
                    ->setPrix($faker->randomFloat(2, 100, 9999))
                    ->setUser($user);

                $manager->persist($peinture);
            }
        }

        $manager->flush();
    }
}
