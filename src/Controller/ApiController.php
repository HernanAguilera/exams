<?php

namespace App\Controller;

use App\Serializers\BaseSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    protected $serializer;
    protected $validator;
    protected $repository;

    public function __construct(ValidatorInterface $validator, BaseSerializer $serializer)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function jsonResponse($data, $config = []): JsonResponse
    {
        if (!array_key_exists('headers', $config)){
            $config['headers'] = [];
        }
        $status_code = $this->getStatusCode();
        if (array_key_exists('status_code', $config)){
            $status_code = $config['status_code'];
            unset($config['status_code']);
        }
        return new JsonResponse(
            $data,
            $status_code,
            $config['headers']
        );
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function response($data, $config = [])
    {
        if (!array_key_exists('headers', $config)){
            $config['headers'] = [];
        }
        $status_code = $this->getStatusCode();
        if (array_key_exists('status_code', $config)){
            $status_code = $config['status_code'];
            unset($config['status_code']);
        }
        return new JsonResponse(
            $this->convertDataResponse($data),
            $status_code,
            $config['headers']
        );
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $errors
     * @param $headers
     * @return JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = [
            'status' => $this->getStatusCode(),
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }


    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $success
     * @param $headers
     * @return JsonResponse
     */
    public function respondWithSuccess($success, $headers = [])
    {
        $data = [
            'status' => $this->getStatusCode(),
            'success' => $success,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }


    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    /**
     * Returns a 422 Unprocessable Entity
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondValidationError($errors)
    {
        return $this->response([
            'errors' => $this->getErrorsArray($errors),
        ], [
            'status_code' => 400
        ]);
    }

    /**
     * Returns a 404 Not Found
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    /**
     * Returns a 201 Created
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function respondCreated($data = [])
    {
        return $this->setStatusCode(201)->response($data);
    }

    // this method allows us to accept JSON payloads in POST requests
    // since Symfony 4 doesn’t handle that automatically:

    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

    public function validateData(String $data, $doctrine, $old_object = null){
        $json_data = $this->cleanNulls($data);
        list($relations, $cleaned_data) = $this->repository->checkRelations($json_data, $doctrine);
        $context = [];
        if (!is_null($old_object))
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $old_object;

        try {
            $obj = $this->serializer->deserialize(json_encode($cleaned_data), $context);
            $this->repository->saveRelations($obj, $relations);
            $errors = $this->validator->validate($obj);
        } catch (\Throwable $th) {
            $obj = null;
            $errors = ['default' => 'Unknown error in recieved data'];
            // dd($th->getTraceAsString());
        }

        return [
            $obj,
            $errors
        ];
    }

    public function getErrorsArray($errors) {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
        }
        return $errorMessages;
    }

    public function convertDataResponse($data, $serializer=null): array
    {
        $serializer = is_null($serializer)? $this->serializer : $serializer;
        return json_decode($serializer->serialize($data), true);
    }

    public function cleanNulls($data)
    {
        $json_data = json_decode($data, true);
        if (!$json_data)
            return [];
        foreach ($json_data as $key => $field) {
            if (is_null($field)){
                unset($json_data[$key]);
            }
        }

        return $json_data;
    }
}