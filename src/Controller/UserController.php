<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Serializers\DTO\RegisterDtoSerializer;
use App\Serializers\Entity\UserSerializer;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/users')]
class UserController extends ApiController
{

    public function __construct(ValidatorInterface $validator,RegisterDtoSerializer $serializer)
    {
        parent::__construct($validator, $serializer);
    }

    #[Route('/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request, UserRepository $userRepository, UserSerializer $userSerializer, JWTTokenManagerInterface $JWTManager): Response
    {
        $json_data = $this->cleanNulls($request->getContent());
        
        try {
            $obj = $this->serializer->deserialize(json_encode($json_data), []);
            $errors = $this->validator->validate($obj);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            $obj = null;
            $errors = ['default' => ['Unknown error in recieved data']];
        }
        if ($obj) {
            $user = $userRepository->findOneBy(['email' => $obj->getEmail()]);
            $errors = $this->getErrorsArray($errors);
            if ($user){
                $obj = null;
                $errors['email'] = ["El email: " . $user->getEmail() . ", se encuentra ya registrado"];
            }
        }
        if (count($errors) > 0){
            return $this->response([
                'errors' => $errors,
            ], [
                'status_code' => 400
            ]);
        }
        $user = $userRepository->createUser($json_data['email'], $json_data['password']);
        $dataResponse = [
            'token' => $JWTManager->create($user),
            'user' => $userSerializer->normalize($user, ['id', 'email'] )
        ];
        return $this->jsonResponse($dataResponse, [ 'status_code' => 201 ]);
    }
}
