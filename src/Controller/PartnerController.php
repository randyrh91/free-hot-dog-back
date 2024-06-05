<?php

namespace App\Controller;

use App\Entity\PartnerEntity;
use App\Repository\PartnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/partner')]
class PartnerController extends AbstractController
{

    private PartnerRepository $repository;

    public function __construct(PartnerRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/{id}', name: 'donate_app_get_partner', methods: 'GET')]
    public function get($id): JsonResponse
    {
        $partner = $this->repository->findOneBy(['id' => $id]);
        return $this->json($partner, Response::HTTP_OK);
    }

    #[Route('', name: 'donate_app_get_all_partner', methods: 'GET')]
    public function getAll(): JsonResponse
    {
        $partners = $this->repository->findAll();
        return $this->json($partners);
    }

    #[Route('', name: 'donate_app_add_partner', methods: 'POST')]
    public function add(Request $request, UserPasswordHasherInterface $hash)
    {
        $data = json_decode($request->getContent(), true);
        
        $partner = new PartnerEntity();
        $encoded = $hash->hashPassword($partner, $data['password']);

        $name = $data['name'];
        $email = $data['email'];
        $password = $encoded;
        $phone = $data['phone'];
        $activationCode = $data['activationCode'];

        if (empty($email) || empty($password)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->repository->savePartner($partner, $name, $email, $password, $phone, $activationCode);
        return new JsonResponse(["status" => "OK"]);
    }

    #[Route('/{id}', name: 'donate_app_update_partner', methods: 'PUT')]
    public function update($id, Request $request): JsonResponse
    {
        $partner = $this->repository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $partner->setName($data['name']);
        empty($data['email']) ? true : $partner->setEmail($data['email']);
        empty($data['password']) ? true : $partner->setPassword($data['password']);
        empty($data['phone']) ? true : $partner->setPhone($data['phone']);
        empty($data['activationCode']) ? true : $partner->setActivationCode($data['activationCode']);

        $updatedPartner = $this->repository->updatePartner($partner);
        return $this->json($updatedPartner, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'donate_app_delete_partner', methods: 'DELETE')]
    public function delete($id): JsonResponse
    {
        $partner = $this->repository->findOneBy(['id' => $id]);
        $this->repository->removePartner($partner);
        return new JsonResponse(['status' => 'Partner deleted'], Response::HTTP_OK);
    }
}
