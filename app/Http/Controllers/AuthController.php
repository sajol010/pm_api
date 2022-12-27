<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\{JsonResponse,Request};
use Illuminate\Support\Facades\{Auth, Validator};

class AuthController extends Controller
{
    /**
     * @POST /api/register
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6' //minimum 6 digit
        ]);

        if ($validator->fails())
            return $this->fail('Please correct the following errors.', $validator->errors(), 422);

        $user = User::create($request->only(['name', 'email', 'password']));
        return $this->success($user, 'User has been added!', 201);
    }

    /**
     * @POST /api/login
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticate(Request $request): JsonResponse
    {
        if (Auth::attempt($request->only(['email', 'password']))){
            $user = Auth::user();
            $user->token = $user->createToken('User token for '.$user->id)->plainTextToken;
            return $this->success($user);
        }
        return $this->fail('Something wrong!', '', 500);

    }
}
