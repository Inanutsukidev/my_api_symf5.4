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

class FacturesFixtures extends Fixture implements FixtureInterface, OrderedFixtureInterface
{

    public function getDependencies()
    {
        return [
            Client::class,
        ];
    }

    public function getOrder()
    {
        return 3;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = faker::create('fr_FR');

        for ($j = 0; $j < 10; $j++) {
            $facture = new Facture;

            $client = $manager->find(Client::class, $faker->numberBetween(1, 10));

            $date_creation = $faker->dateTimeBetween("-6 months");
            $date_facturation = $faker->dateTimeBetween($date_creation, '+10 days');
            $date_intervention = $faker->dateTimeBetween($date_creation, '+10 days');
            $date_paiement = $faker->dateTimeBetween($date_facturation, '+10 days');

            $facture
                ->setEtat($faker->randomElement(['acquitte', 'en attente']))
                ->setDocType($faker->randomElement(['brouillon', 'devis', 'facture', 'facture', 'facture', 'devis']))
                ->setDevise('EUR')
                ->setClient($client)
                ->setNumFacture("fact-" . ($j + 1))
                ->setDateCreation($date_creation)
                ->setDateIntervention($date_intervention);

            if ($facture->getDocType() == 'facture') {
                $facture
                    ->setEtat('en attente')
                    ->setDateFacturation($date_facturation);
            }

            if ($facture->getDocType() == 'devis') {
                $facture
                    ->setEtat('acquittée')
                    ->setDateFacturation($date_facturation)
                    ->setDatePaiement($date_paiement);
            }
            $produit = $manager->find(Produit::class, $faker->numberBetween(1, 10));

            $facture->addProduit($produit);

            $manager->persist($facture);
        }

        $manager->flush();
    }
}
