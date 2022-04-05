<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Faker\Factory as faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Provider\fr_FR\Address as adresse;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ClientsFixtures extends Fixture implements FixtureInterface, OrderedFixtureInterface
{
    public const CLIENT = "client";

    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = faker::create('fr_FR');
        $adresse = new adresse($faker);

        for ($i = 0; $i < 10; $i++) {
            $client = new Client();

            $city_temp = $faker->citysuffix();
            $city_name = (substr($city_temp, 0, 1) == "-") ? $faker->firstName() . $faker->citysuffix() : $faker->firstName() . " " . $faker->citysuffix();

            $client
                ->setGenre($faker->randomElement(['Mr.', 'Mme.']))
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setEmail($faker->email())
                ->setTelPt($faker->phoneNumber())
                ->setTelFix($faker->phoneNumber())
                ->setAdressePrefix($adresse->streetPrefix())
                ->setAdresseNumero($faker->buildingNumber())
                ->setAdresseNom($city_name)
                ->setAdresseCp(str_replace(" ", "",$faker->postcode()))
                ->setAdresseVille($faker->region())
                ->setDateCreation($faker->dateTimeBetween("-6 months"));

            $manager->persist($client);
        }

        $this->setReference(self::CLIENT, $client);

        $manager->flush();
    }
}
