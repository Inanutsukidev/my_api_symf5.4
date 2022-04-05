<?php

namespace App\Routes;

use App\Entity\Client;
use App\Entity\Facture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DevisController extends AbstractController
{
    /**
     * @Route("/devis", name="app_devis")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $client = $em->find(Client::class, 1);
        $devis = $em->getRepository(Facture::class)->findOneBy(['client' => $client]);

        return $this->render('devis/index.html.twig', [
            'client' => $client,
            'devis' => $devis,
        ]);
    }
}
