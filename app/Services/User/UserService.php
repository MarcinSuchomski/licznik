<?php

namespace App\Services\User;


use App\Exceptions\UserWithThatEmailAlreadyExists;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\BaseService;
use App\Validators\User\UserValidator;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use Laravel\Passport\Bridge\UserRepository;

class UserService extends BaseService
{
    /**
     * UserService constructor.
     * @param UserRepository $repo
     * @param UserValidator $validator
     */
    public function __construct(UserRepository $repo, UserValidator $validator)
    {
        $this->repo = $repo;
        $this->validator = $validator;
    }


    /**
     * Autentykacja uzytkownika generowanie token'a
     * @param \http\Client\Request $request
     *
     */
    public function login(Request $request)
    {
        $requestData = $request->all();

        $validateObject = $this->validator->validate($requestData);
        if (!$validateObject->valid) {
            return response()->json([
                'errors' => $validateObject->errors
            ], 422);
        }

        if(!auth()->attempt($requestData)){
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return  response(['user' => auth()->user(), 'access_token' => $accessToken], 200);
    }

    /**
     * Rejestacja nowego uzytkownika
     * @param \http\Client\Request $request
     *
     */
    public function register(Request $request)
    {
        $requestData = $request->all();

        if ($this->repo->getModel()->whereEmail($requestData['email'])->count()) {
            return response()->json([
                'errors' => "email already exist"
            ], 422);
        }

        $validateObject = $this->validator->registationValidate($requestData);
        if (!$validateObject->valid) {
            return response()->json([
                'errors' => $validateObject->errors
            ], 422);
        }

        $requestData['password'] = Hash::make($requestData['password']);

        User::create($requestData);

        return response([ 'status' => 'success', 'message' => 'User successfully register.' ], 200);
    }
}
