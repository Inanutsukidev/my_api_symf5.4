<?php

namespace App\Routes;

use Exception;
use App\Entity\Client;
use App\Controller\ClientController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use RepositoryTrait;

/**
 * @Route("/api", name="api_")
 */
class ClientApiController extends ClientController
{
    use RepositoryTrait;

    /**
     * @route("/clients", name="list_client", methods={"GET"})
     */
    public function listeClient(EntityManagerInterface $em)
    {
        try {
            $clients_db = $em->getRepository(Client::class)->findAll();

            $clients = [];

            foreach ($clients_db as $client) {
                $clients[$client->getId()] =
                    $this->getInfosClient($client);
            }
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(json_encode($clients), JsonResponse::HTTP_OK, ["Content-Type" => "application/json"], true);
    }

    /**
     * @Route("/client/{id}", name="app_client_show", methods={"GET"})
     */
    public function showClient($id, EntityManagerInterface $em): JsonResponse
    {
        try {
            $client = $em->getRepository(Client::class)->find($id);
            $client_infos = $this->getInfosClient($client);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(json_encode($client_infos), JsonResponse::HTTP_OK, ["Content-Type" => "application/json"], true);
    }

    /**
     * @Route("/client", name="app_client_create", methods={"POST"})
     */
    public function createClient(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        try {
            $data = json_encode($request->query->all());
            $client = $serializer->deserialize($data, Client::class, "json");
            $em->persist($client);
            $em->flush();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse("L'utilisateur a bien été créé", JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/client/{id}", name="app_client_modify", methods={"PATCH"})
     */
    public function modifyClient(Request $request, EntityManagerInterface $em, $id): JsonResponse
    {
        try {
            $data = $request->query->all();
            $set_str = $this->makeSettersQuery($data);

            $conn = $em->getConnection();

            $sql = "
            UPDATE client
            SET $set_str
            WHERE id = $id
            ;";

            $stmt = $conn->prepare($sql);
            $stmt->executeQuery();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse("L'utilisateur #$id a bien été mis à jour", JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/client/{id}", name="app_client_delete", methods={"DELETE"})
     */
    public function deleteClient($id, EntityManagerInterface $em): JsonResponse
    {
        $client = $em->getRepository(Client::class)->find($id);

        try {
            $em->remove($client);
            $em->flush();
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);
    }
}
