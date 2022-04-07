<?php

namespace App\Routes;

use App\Controller\FactureController;
use App\Entity\Facture;
use App\Entity\FactureProduit;
use App\Entity\Produit;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use RepositoryTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api", name="api_")
 */
class FactureApiController extends FactureController
{
    use RepositoryTrait;

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
            $factures[$facture->getId()] = $this->getInfosfacture($facture, $em);
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
            $facture_infos = $this->getInfosfacture($facture, $em);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(json_encode($facture_infos), JsonResponse::HTTP_OK, ["Content-Type" => "application/json"], true);
    }

    /**
     * @Route("/facture/{id}", name="app_facture_modify", methods={"PATCH"})
     */
    public function modifyFacture(Request $request, EntityManagerInterface $em, $id): JsonResponse
    {
        try {
            $data = $request->query->all();
            $set_str = $this->makeSettersQuery($data);

            $conn = $em->getConnection();

            $sql = "
            UPDATE facture
            SET $set_str
            WHERE id = $id
            ;";

            $stmt = $conn->prepare($sql);
            $stmt->executeQuery();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse("La facture #$id a bien été mis à jour", JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/facture/{fact_id}/produit/{prd_id}", name="app_facture_add_produit", methods={"PATCH"})
     */
    public function addProduitToFacture($fact_id, $prd_id, EntityManagerInterface $em): JsonResponse
    {
        try {
            $fp = $em->getRepository(FactureProduit::class)->findOneBy(['facture' => $fact_id, 'produit' => $prd_id]);
            if ($fp) {
                $fp->setQuantite($fp->getQuantite() + 1);
            } else {
                $produit = $em->getRepository(Produit::class)->find($prd_id);
                $facture = $em->getRepository(Facture::class)->find($fact_id);
                $fp = new FactureProduit;
                $fp
                    ->setFacture($facture)
                    ->setProduit($produit)
                    ->setQuantite(1);
            }

            $em->persist($fp);
            $em->flush();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('Le produit a bien été ajouté', JsonResponse::HTTP_NO_CONTENT);
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
