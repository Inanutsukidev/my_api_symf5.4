<?php

namespace App\Routes;

use App\Controller\ProduitController;
use App\Entity\Produit;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use RepositoryTrait;

/**
 * @Route("/api", name="api_")
 */
class ProduitApiController extends ProduitController
{
    use RepositoryTrait;

    /**
     * @route("/produits", name="list_produit", methods={"GET"})
     */
    public function listProduct(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        try {
            $produits_db = $em->getRepository(Produit::class)->findAll();

            $produits = $serializer->serialize($produits_db, "json");
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        $produits = [];

        foreach ($produits_db as $produit) {
            $produits[$produit->getId()] = $this->getInfosProduit($produit);
        }

        return new JsonResponse(json_encode($produits), JsonResponse::HTTP_OK, ["Content-Type" => "application/json"], true);
    }

    /**
     * @Route("/produit/{id}", name="app_produit_show", methods={"GET"})
     */
    public function showProduit($id, EntityManagerInterface $em): JsonResponse
    {
        try {
            $produit = $em->getRepository(Produit::class)->find($id);
            $produit_infos = $this->getInfosProduit($produit);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(json_encode($produit_infos), JsonResponse::HTTP_OK, ["Content-Type" => "application/json"], true);
    }

    /**
     * @Route("/produit", name="app_produit_create", methods={"POST"})
     */
    public function createProduct(Request $request, SerializerInterface $serializer,  EntityManagerInterface $em): JsonResponse
    {
        $data = $request->getContent();

        try {
            $produit = $serializer->deserialize($data, Produit::class, "json");
            $em->persist($produit);
            $em->flush();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('', JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/produit/{id}", name="app_produit_modify", methods={"PATCH"})
     */
    public function modifyProduct(Request $request, EntityManagerInterface $em, $id): JsonResponse
    {
        try {
            $data = $request->query->all();
            $set_str = $this->makeSettersQuery($data);

            $conn = $em->getConnection();

            $sql = "
            UPDATE produit
            SET $set_str
            WHERE id = $id
            ;";

            $stmt = $conn->prepare($sql);
            $stmt->executeQuery();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse("Le produit #$id a bien été mis à jour", JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/produit/{id}", name="app_produit_delete", methods={"DELETE"})
     */
    public function deleteProduct(Produit $produit, EntityManagerInterface $em): JsonResponse
    {
        try {
            $em->remove($produit);
            $em->flush();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);
    }
}
