<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/company')]
class CompanyController extends AbstractController
{
    private CompanyRepository $repository;

    public function __construct(CompanyRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/{id}', name: 'donate_app_get_company', methods: 'GET')]
    public function get($id): JsonResponse
    {
        $company = $this->repository->findOneBy(['id' => $id]);
        return $this->json($company, Response::HTTP_OK);
    }

    #[Route('/', name: 'donate_app_get_all_company', methods: 'GET')]
    public function getAll(): JsonResponse
    {
        $companies = $this->repository->findAll();
        return $this->json($companies);
    }

    #[Route('/', name: 'donate_app_add_company', methods: 'POST')]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $address = $data['address'];
        $description = $data['description'];
        $photo = $data['photo'];
        if (empty($name)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->repository->saveCompany($name, $address, $description, $photo);
        return new JsonResponse(['status' => 'company created!'], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'donate_app_update_company', methods: 'PUT')]
    public function update($id, Request $request): JsonResponse
    {
        $company = $this->repository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $company->setName($data['name']);

        $updatedCompany = $this->repository->updateCompany($company);
        return $this->json($updatedCompany, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'donate_app_delete_company', methods: 'DELETE')]
    public function delete($id): JsonResponse
    {
        $company = $this->repository->findOneBy(['id' => $id]);
        $this->repository->removeCompany($company);
        return new JsonResponse(['status' => 'company deleted'], Response::HTTP_OK);
    }
}
