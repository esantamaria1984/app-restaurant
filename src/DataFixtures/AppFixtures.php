<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Restaurant;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    //Habilitamos el servicio para encriptar contraseñas

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        //Faker en español
        $faker = Factory::create('es_ES');

        //Creamos 10 usuarios
        for ($i = 0; $i < 10; $i++) {
            $user = new user();

            //Creamos el email
            $user->setEmail($faker->unique()->safeEmail());

            //Roles como array JSON
            $user->setRoles(['ROLE_USER']);

            //Encriptamos la contraseña
            $hashedPassword = $this->passwordHasher->hashPassword($user, '1234');
            $user->setPassword($hashedPassword);

            //Creamos entre 1 y 5 restaurantes para cada usuario
            $restaurantsCount = rand(1, 5);

            for ($j=0; $j < $restaurantsCount; $j++) { 
                $restaurant = new Restaurant();
                $restaurant->setName($faker->company());
                $restaurant->setAddress($faker->address());
                $restaurant->setPhone($faker->phoneNumber());

                //Asociamos el restaurante con el usuario
                $restaurant->setUser($user);
                $user->addRestaurant($restaurant);

                $manager->persist($restaurant);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}
