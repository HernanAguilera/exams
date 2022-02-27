<?php

namespace App\Controller;

use App\DTO\Schedule\ScheduleCreationDTO;
use App\Entity\Schedule;
use App\Repository\ExamRepository;
use App\Repository\ScheduleRepository;
use App\Serializers\DTO\ScheduleCreationDtoSerializer;
use App\Serializers\Entity\ScheduleSerializer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/schedule')]
class ScheduleController extends ApiController
{
    public function __construct(
        ValidatorInterface $validator, 
        ScheduleSerializer $serializer,
        ScheduleRepository $scheduleRepository,
        ExamRepository $examRepository
    )
    {
        parent::__construct($validator, $serializer);
        $this->repository = $scheduleRepository;
        $this->examRepository = $examRepository;
    }

    #[Route('/', name: 'schedule_index', methods: ['GET'])]
    public function index(ScheduleRepository $scheduleRepository, Request $request, ScheduleSerializer $serializer): Response
    {

        if ($request->query->get('exam'))
            $filters['exam'] = $request->query->get('exam');
        if ($request->query->get('start_date'))
            $filters['start_date'] = $request->query->get('start_date');
        if ($request->query->get('end_date'))
            $filters['end_date'] = $request->query->get('end_date');

        if (count($filters) === 0) {
            $schedules = $scheduleRepository->findAll();
        } else {
            $schedules =  $scheduleRepository->search($filters);
        }

        $schedules = array_map(function($schedule) use($serializer) {
            $schedule = $serializer->normalize($schedule, $serializer->getFields());
            $schedule['date'] = date('Y-m-d', $schedule['date']['timestamp']);
            return $schedule;
        }, $schedules);

        return $this->jsonResponse($schedules);
    }

    #[Route('/', name: 'schedule_new', methods: ['POST'])]
    public function new(Request $request,
                        ScheduleCreationDtoSerializer $DTOserializer,
                        ScheduleSerializer $serializer,
                        ValidatorInterface $validator,
                        ScheduleRepository $scheduleRepository): Response
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

        $exam = $this->examRepository->find($json_data['exam']);
        $splited_date = explode('-', $json_data['date']);
        if (!checkdate($splited_date[1], $splited_date[2], $splited_date[0]))
            $error['date'] = 'Date: ' . $json_data['date'] . ', is not valid';

        if (count($errors) > 0) {
            return $this->response([
                'errors' => $errors,
            ], [
                'status_code' => 400
            ]);
        }
        
        $schedule = $scheduleRepository->createSchedule($exam, new DateTime($json_data['date']));
        $dataResponse = $serializer->normalize($schedule, $serializer->getFields());
        $dataResponse['date'] = date('Y-m-d', $dataResponse['date']['timestamp']);
        return $this->jsonResponse($dataResponse, ['status_code' => 201]);
    }

    #[Route('/{id}', name: 'schedule_show', methods: ['GET'])]
    public function show(Schedule $schedule, ScheduleSerializer $serializer): Response
    {
        $scheduleResponse = $serializer->normalize($schedule, $serializer->getFields());
        $scheduleResponse['date'] = $serializer->convertDate($scheduleResponse['date']['timestamp']);
        return $this->jsonResponse($scheduleResponse);
    }

    #[Route('/{id}', name: 'schedule_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request,
                         Schedule $schedule,
                         ScheduleCreationDtoSerializer $DTOserializer,
                         ScheduleSerializer $serializer,
                         ValidatorInterface $validator,
                         ScheduleRepository $scheduleRepository): Response
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
        $exam = null;
        if (key_exists('exam', $json_data)){
            $exam = $this->examRepository->find($json_data['exam']);
            if (!$exam)
                $error['exam'] = 'Exam not found';
            else
                $data_to_modified['exam'] = $exam;
        }

        if (count($errors) > 0) {
            return $this->response([
                'errors' => $errors,
            ], [
                'status_code' => 400
            ]);
        }

        $schedule = $scheduleRepository->updateSchedule($schedule, $exam, new DateTime($json_data['date']));

        $dataResponse = $serializer->normalize($schedule, $serializer->getFields());
        $dataResponse['date'] = date('Y-m-d', $dataResponse['date']['timestamp']);
        return $this->jsonResponse($dataResponse);
    }

    #[Route('/{id}', name: 'schedule_delete', methods: ['DELETE'])]
    public function delete(Request $request, Schedule $schedule, EntityManagerInterface $entityManager, ScheduleRepository $scheduleRepository): Response
    {
        $entityManager->remove($schedule);
        $entityManager->flush();
        $data = [
            'message' => "Schedule deleted successfully",
            'objects' => $scheduleRepository->findAll()
        ];
        return $this->response($data);
    }
}
