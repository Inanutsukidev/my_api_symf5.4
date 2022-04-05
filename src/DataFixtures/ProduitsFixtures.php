<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Produit;
use Faker\Factory as faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ProduitsFixtures extends Fixture implements FixtureInterface, OrderedFixtureInterface
{
    public const PRODUIT = "produit";

    public function getDependencies()
    {
        return [
            Facture::class,
        ];
    }

    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = faker::create('fr_FR');

        for ($j = 0; $j < 10; $j++) {
            $produit = new Produit;

            $client = $manager->find(Client::class, $faker->numberBetween(1, 10));

            $ref = strtoupper($faker->bothify('???-???/???-???'));
            $produit
                ->setType($faker->randomElement(['Climatiseur_fixe_mono-split', 'Climatiseur_fixe_multi-split']))
                ->setLib("MURALE - " . strtoupper($faker->bothify('???/???-??-??')) . " - Essentiel -Inverter - Réversible")
                ->setMarque("Mitsubishi electric")
                ->setRef($ref)
                ->setFournisseur('Richardson')
                ->setPrixTtc($faker->randomFloat(2, 349.01, 621.09));


            $manager->persist($produit);
        }

        $this->setReference(self::PRODUIT, $client);

        $manager->flush();
    }
}
