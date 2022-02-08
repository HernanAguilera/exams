<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Repository\ExamRepository;
use App\Serializers\Entity\ExamSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/exam')]
class ExamController extends ApiController
{
    public function __construct(
        ValidatorInterface $validator, 
        ExamSerializer $serializer,
        ExamRepository $examRepository
    )
    {
        parent::__construct($validator, $serializer);
        $this->repository = $examRepository;
    }

    #[Route('/', name: 'exam_index', methods: ['GET'])]
    public function index(ExamRepository $examRepository): Response
    {
        return $this->response($examRepository->findAll());
    }

    #[Route('/', name: 'exam_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        list($exam, $errors) = $this->validateData(
            json_encode(json_decode($request->getContent(), true)),
            $doctrine
        );

        if (count($errors) > 0) {
            return $this->respondValidationError($errors);
        }

        $entityManager->persist($exam);
        $entityManager->flush();

        $data = [
            'success' => "Exam added successfully",
            'object' => $exam
        ];
        return $this->response($data, ['status_code' => 201]);
    }

    #[Route('/{id}', name: 'exam_show', methods: ['GET'])]
    public function show(Exam $exam): Response
    {
        return $this->response($exam);
    }

    #[Route('/{id}', name: 'exam_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Exam $exam, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        list($exam, $errors) = $this->validateData(
            json_encode(json_decode($request->getContent(), true)),
            $doctrine,
            $exam
        );

        if (count($errors) > 0) {
            return $this->respondValidationError($errors);
        }

        $entityManager->persist($exam);
        $entityManager->flush();

        $data = [
            'success' => "Exam updated successfully",
            'object' => $exam
        ];
        return $this->response($data);
    }

    #[Route('/{id}', name: 'exam_delete', methods: ['DELETE'])]
    public function delete(Request $request, Exam $exam, EntityManagerInterface $entityManager, ExamRepository $examRepository): Response
    {
        $entityManager->remove($exam);
        $entityManager->flush();
        $data = [
            'message' => "Exam deleted successfully",
            'objects' => $examRepository->findAll()
        ];
        return $this->response($data);
    }
}
