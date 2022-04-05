<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    public function getInfosClient(Client $client): Array
    {
        $client_infos = [];

        $client_infos = [
            'genre' => $client->getGenre(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'adresse' => $client->getAdresseComplete(),
            'tel_fix' => $client->getTelFix(),
            'tel_pt' => $client->getTelPt(),
            'email' => $client->getEmail(),
        ];

        return $client_infos;
    }
}
