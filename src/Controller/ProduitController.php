<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    public function getInfosProduit(Produit $produit): Array
    {
        $produit_infos = [];

        $produit_infos = [
            'type' => $produit->getType(),
            'lib' => $produit->getLib(),
            'marque' => $produit->getMarque(),
            'ref' => $produit->getRef(),
            'fournisseur' => $produit->getFournisseur(),
            'prix_ttc' => $produit->getPrixTtc()
        ];

        return $produit_infos;
    }
}
