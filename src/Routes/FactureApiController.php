<?php

namespace App\Routes;

use App\Controller\FactureController;
use App\Entity\Facture;
use App\Entity\Produit;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api", name="api_")
 */
class FactureApiController extends FactureController
{
    /**
     * @route("/factures", name="list_facture", methods={"GET"})
     */
    public function listeFacture(EntityManagerInterface $em)
    {
        try {
            $factures_db = $em->getRepository(Facture::class)->findAll();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        $factures = [];

        foreach ($factures_db as $facture) {
            $factures[$facture->getId()] = $this->getInfosfacture($facture);
        }

        return new JsonResponse(json_encode($factures), JsonResponse::HTTP_OK, ["Content-Type" => "application/json"], true);
    }

    /**
     * @Route("/facture/{id}", name="app_facture_show", methods={"GET"})
     */
    public function showFacture($id, EntityManagerInterface $em): JsonResponse
    {
        try {
            $facture = $em->getRepository(Facture::class)->find($id);
            $facture_infos = $this->getInfosfacture($facture);

        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(json_encode($facture_infos), JsonResponse::HTTP_OK, ["Content-Type" => "application/json"], true);
    }

    /**
     * @Route("/facture/{fact_id}/produit/{prd_id}", name="app_facture_add_produit", methods={"PATCH"})
     */
    public function addProduitToFacture($fact_id, $prd_id, EntityManagerInterface $em): JsonResponse
    {
        $facture = $em->getRepository(Facture::class)->find($fact_id);
        $produit = $em->getRepository(Produit::class)->find($prd_id);

        try {
            $facture->addProduit($produit);
            $em->persist($facture);
            $em->flush();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('', JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/facture", name="app_facture_create", methods={"POST"})
     */
    public function createFacture(Request $request, SerializerInterface $serializer,  EntityManagerInterface $em): JsonResponse
    {
        $data = json_encode($request->query->all());

        try {
            $facture = $serializer->deserialize($data, Facture::class, "json");
            $em->persist($facture);
            $em->flush();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('', JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/facture/{id}", name="app_facture_delete", methods={"DELETE"})
     */
    public function deleteFacture($id, EntityManagerInterface $em): JsonResponse
    {
        $facture = $em->getRepository(Facture::class)->find($id);

        try {
            $em->remove($facture);
            $em->flush();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);
    }
}
