<?php

namespace App\Controller;

use App\Entity\Facture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FactureController extends AbstractController
{
    public function getInfosfacture(Facture $facture): array
    {
        $facture_infos = [];

        $facture_infos = [
            'num_facture' => $facture->getNumFacture(),
            'etat' => $facture->getEtat(),
            'date_creation' => $facture->getDateCreation()->format('d-m-Y'),
            'date_intervention' => $facture->getDateIntervention()->format('d-m-Y'),
            'date_facturation' => $facture->getDateFacturation()->format('d-m-Y'),
            'date_paiement' => $facture->getDatePaiement()->format('d-m-Y'),
            'montant_total_ttc' => $facture->getMontantTotalTtc(),
            'devise' => $facture->getDevise(),
            'doc_type' => $facture->getDocType(),
            'client_id' => ($facture->getClient())->getId(),
            'produits' => array(),
        ];

        $produits = $facture->getProduits();

        foreach ($produits as $produit) {
            $facture_infos['produits'][$produit->getId()] = array(
                'lib' => $produit->getLib()
            );
        }

        return $facture_infos;
    }
}
