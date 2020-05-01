<?php

namespace App\Http\Controllers;

use App\OnlineApplication;
use App\Services\OAuthClientService;
use App\Services\OnlineApplicationService;
use App\Services\userService;
use Illuminate\Http\Request;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use App\Http\Requests\UserBaseRequest;


class UserController extends Controller
{
    private $userService;


    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth');
    }

    public function addOnlineApplicationToUser(Request $request)
    {
        try {
            $this->userService->addOnlineApplicationToUser(auth()->id(), $request->id);
            return redirect('/application');
        } catch (QueryException $ex) {
            return redirect('/application')->withErrors(['Application already added']);
        }
    }
}
