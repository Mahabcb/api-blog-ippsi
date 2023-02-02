<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Articles;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $plainPassword = 'password';
        $user = (new User())
                ->setEmail('user@mail.fr');
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));

        $admin = (new User())
                ->setEmail('admin@mail.fr')
                ->setRoles(['ROLE_ADMIN']);
        $plainPasswordAdmin = "password";
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $plainPasswordAdmin));

        $manager->persist($user);
        $manager->persist($admin);
        

        for ($i = 0; $i < 5; $i++) {
            $category = (new Category())
                    ->setName($faker->word());
            $manager->persist($category);
            for ($j = 0; $j < 60; $j++) {
                $article = (new Articles())
                        ->setTitle($faker->sentence())
                        ->setContent($faker->paragraphs(3, true))
                        ->addCategory($category);
                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
