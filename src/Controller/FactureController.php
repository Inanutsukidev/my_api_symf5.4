<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Produit;
use App\Entity\FactureProduit;
use Doctrine\ORM\EntityManagerInterface as em;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FactureController extends AbstractController
{
    public function getInfosfacture(Facture $facture, em $em): array
    {
        $facture_infos = [];

        $facture_infos = [
            'num_facture' => $facture->getNumFacture(),
            'etat' => $facture->getEtat(),
            'date_creation' => $facture->getDateCreation()->format('d-m-Y'),
            'date_intervention' => (!empty($facture->getDateFacturation()))  ? $facture->getDateIntervention()->format('d-m-Y') : "",
            'date_facturation' => (!empty($facture->getDateFacturation())) ? $facture->getDateFacturation()->format('d-m-Y') : "",
            'date_paiement' => (!empty($facture->getDatePaiement())) ? $facture->getDatePaiement()->format('d-m-Y') : "",
            'montant_total_ttc' => $facture->getMontantTotalTtc(),
            'devise' => $facture->getDevise(),
            'doc_type' => $facture->getDocType(),
            'client_id' => ($facture->getClient())->getId(),
            'produits' => array(),
        ];

        $fp = $em->getRepository(FactureProduit::class)->findBy(['facture' => $facture]);

        foreach ($fp as $produit) {
            $prd = $em->getRepository(Produit::class)->find($produit->getProduit());
            $facture_infos['produits'][] = array(
                'id' => $prd->getId(),
                'lib' => $prd->getLib(),
                'quantite' => $produit->getQuantite()
            );
        }

        return $facture_infos;
    }
}
