<?php

namespace App\Controller;

use App\Entity\Test;
use App\Repository\ExamRepository;
use App\Repository\TestRepository;
use App\Repository\UserRepository;
use App\Serializers\DTO\ExamRegistrationDtoSerializer;
use App\Serializers\DTO\ExamRegistrationUpdatingDtoSerializer;
use App\Serializers\Entity\TestSerializer;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/registration/exam')]
class ExamRegistrationController extends ApiController
{
    protected $userRepository;
    protected $examRepository;
    protected $testRepository;

    public function __construct(UserRepository $userRepository,
                                ExamRepository $examRepository,
                                TestRepository $testRepository)
    {
        $this->userRepository = $userRepository;
        $this->examRepository = $examRepository;
        $this->testRepository = $testRepository;
    }

    #[Route('/', name: 'exams_list', methods: ['GET'])]
    public function list(Request $request, ExamRegistrationUpdatingDtoSerializer $serializer)
    {
        $json_data = $this->cleanNulls($request->getContent());
        $filters = [];

        if (key_exists('status', $json_data))
            $filters['status'] = $json_data['status'];

        if (count($filters) === 0) {
            $tests = $this->testRepository->findAll();
        } else {
            $tests =  $this->testRepository->findBy($filters);
        }

        $tests = array_map(function($test) use($serializer) {
            return $serializer->normalize($test, ['id', 'user' => ['id', 'email'], 'exam' => ['id', 'name']]);
        }, $tests);

        return $this->jsonResponse([
            'registers' => $tests
        ]);
    } 

    #[Route('/', name: 'exam_registration', methods: ['POST'])]
    public function register(Request $request, ExamRegistrationDtoSerializer $serializer, ValidatorInterface $validator)
    {
        $json_data = $this->cleanNulls($request->getContent());
        try {
            $objDTO = $serializer->deserialize(json_encode($json_data), []);
            $errors = $validator->validate($objDTO);
        } catch (\Throwable $th) {
            $objDTO = null;
            $errors = ['default' => ['Unknown error in recieved data']];
            dd($th->getMessage());
        }
        
        if (count($errors) > 0) {
            return $this->jsonResponse(
                ['errors' => $this->getErrorsArray($errors)], 
                ['status_code' => 400]
            );
        }

        $user = $this->userRepository->find($json_data['user']);
        $exam = $this->examRepository->find($json_data['exam']);
        $splited_date = explode('-', $json_data['date']);
        if (!$user)
            $error['user'] = 'User not found';
        if (!$exam)
            $error['exam'] = 'Exam not found';
        if (!checkdate($splited_date[1], $splited_date[2], $splited_date[0]))
            $error['date'] = 'Date: ' . $json_data['date'] . ', is not valid';
        
        if (count($errors) > 0) {
            return $this->response([
                'errors' => $errors,
            ], [
                'status_code' => 400
            ]);
        }

        $test = $this->testRepository->registerUserToExam($user, $exam, new DateTime($json_data['date']));

        $dataResponse = $serializer->normalize($test, ['id', 'user' => ['id', 'email'], 'exam' => ['id', 'name'], 'date']);
        $dataResponse['date'] = date('Y-m-d', $dataResponse['date']['timestamp']);
        return $this->jsonResponse($dataResponse, ['status_code' => 201]);
    }

    #[Route('/{id}', name: 'exam_registration_show', methods: ['GET'])]
    public function show(Test $test, TestSerializer $serializer) {
        $testResponse = $serializer->normalize($test, $serializer->getFields());
        $testResponse['date'] = $serializer->convertDate($testResponse['date']['timestamp']);
        return $this->jsonResponse($testResponse);
    }

    #[Route('/{id}', name: 'exam_registration_edit', methods: ['PUT'])]
    public function edit(Test $test, Request $request, ExamRegistrationUpdatingDtoSerializer $DTOserializer, ValidatorInterface $validator, TestSerializer $serializer)
    {
        $json_data = $this->cleanNulls($request->getContent());

        try {
            $objDTO = $DTOserializer->deserialize(json_encode($json_data), []);
            $errors = $validator->validate($objDTO);
        } catch (\Throwable $th) {
            $objDTO = null;
            $errors = ['default' => ['Unknown error in recieved data']];
        }

        if (count($errors) > 0) {
            return $this->jsonResponse(
                ['errors' => $this->getErrorsArray($errors)], 
                ['status_code' => 400]
            );
        }

        $data_to_modified = [];
        if (key_exists('user', $json_data)){
            $user = $this->userRepository->find($json_data['user']);
            if (!$user)
                $error['user'] = 'User not found';
            else
                $data_to_modified['user'] = $user;
        }
        if (key_exists('exam', $json_data)){
            $exam = $this->examRepository->find($json_data['exam']);
            if (!$exam)
                $error['exam'] = 'Exam not found';
            else
                $data_to_modified['exam'] = $exam;
        }
        if (key_exists('date', $json_data)){
            $splited_date = explode('-', $json_data['date']);
            if (!checkdate($splited_date[1], $splited_date[2], $splited_date[0]))
                $error['date'] = 'Date: ' . $json_data['date'] . ', is not valid';
            else
                $data_to_modified['date'] = new \DateTime($json_data['date']);
        }
        
        if (count($errors) > 0) {
            return $this->response([
                'errors' => $errors,
            ], [
                'status_code' => 400
            ]);
        }

        $test = $this->testRepository->updatedRegister($test, $data_to_modified);

        $testResponse = $serializer->normalize($test, $serializer->getFields());
        $testResponse['date'] = $serializer->convertDate($testResponse['date']['timestamp']);
        return $this->jsonResponse($testResponse, ['status_code' => 200]);
    }

    #[Route('/{id}', name: 'exam_registration_cancel', methods: ['DELETE'])]
    public function cancel(Test $test)
    {
        try {
            $this->testRepository->cancelRegister($test);
        } catch (\Throwable $th) {
            return $this->response([
                "error" => $th->getMessage()
            ]);    
        }

        return $this->jsonResponse([
            "message" => "Register cancelled"
        ]);
    }
}
