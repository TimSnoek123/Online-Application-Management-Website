<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserBaseRequest;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view("login");
    }

    public function register(){
        return view('register');
    }

    public function createAccount(UserBaseRequest $request)
    {
        $user = new User();
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->save();

        Auth::login($user);

        return redirect("/");
    }

    public function login(UserBaseRequest $request)
    {
            $userData = $request->only('email', 'password');

            $jwt = Auth::attempt($userData);

            if (!$jwt) {
                return response()->json([
                    'succes' => false,
                    'message' => 'invalid email or password',
                ], Response::HTTP_UNAUTHORIZED);
            }


            return response()->json([
                'success' => true,
            ], Response::HTTP_OK);
    }
}
