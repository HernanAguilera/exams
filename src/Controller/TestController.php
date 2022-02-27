<?php

namespace App\Controller;

use App\Entity\Test;
use App\Repository\TestRepository;
use App\Serializers\Entity\TestSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tests')]
class TestController extends ApiController
{
    public function __construct(
        ValidatorInterface $validator, 
        TestSerializer $serializer,
        TestRepository $testRepository
    )
    {
        parent::__construct($validator, $serializer);
        $this->repository = $testRepository;
    }

    #[Route('/', name: 'test_index', methods: ['GET'])]
    public function index(TestRepository $testRepository, Request $request, TestSerializer $serializer): Response
    {
        $json_data = $this->cleanNulls($request->getContent());
        $filters = [];

        if ($request->query->get('name'))
            $filters['name'] = $request->query->get('name');
        if ($request->query->get('company'))
            $filters['company'] = $request->query->get('company');

        if (count($filters) === 0) {
            $tests = $testRepository->findAll();
        } else {
            $tests =  $testRepository->search($filters);
        }

        $tests = array_map(function($test) use($serializer) {
            return $serializer->normalize($test, ['id', 'name', 'companies' => ['id', 'commercialName']]);
        }, $tests);

        return $this->jsonResponse($tests);
    }

    #[Route('/', name: 'test_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        list($test, $errors) = $this->validateData(
            json_encode(json_decode($request->getContent(), true)),
            $doctrine
        );

        if (count($errors) > 0) {
            return $this->respondValidationError($errors);
        }

        $entityManager->persist($test);
        $entityManager->flush();

        $data = [
            'success' => "Test added successfully",
            'object' => $test
        ];
        return $this->response($data, ['status_code' => 201]);
    }

    #[Route('/{id}', name: 'test_show', methods: ['GET'])]
    public function show(Test $test): Response
    {
        return $this->response($test);
    }

    #[Route('/{id}', name: 'test_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Test $test, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        list($test, $errors) = $this->validateData(
            json_encode(json_decode($request->getContent(), true)),
            $doctrine,
            $test
        );

        if (count($errors) > 0) {
            return $this->respondValidationError($errors);
        }

        $entityManager->persist($test);
        $entityManager->flush();

        $data = [
            'success' => "Test updated successfully",
            'object' => $test
        ];
        return $this->response($data);
    }

    #[Route('/{id}', name: 'test_delete', methods: ['DELETE'])]
    public function delete(Request $request, Test $test, EntityManagerInterface $entityManager, TestRepository $testRepository): Response
    {
        $entityManager->remove($test);
        $entityManager->flush();
        $data = [
            'message' => "Test deleted successfully",
            'objects' => $testRepository->findAll()
        ];
        return $this->response($data);
    }
}
