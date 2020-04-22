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

class OnlineApplicationController extends Controller
{
    private $onlineApplicationService;
    private $OAuthClientService;
    private $userService;


    public function __construct(OnlineApplicationService $onlineApplicationService, OAuthClientService $OAuthClientService, UserService $userService)
    {
        $this->userService = $userService;
        $this->OAuthClientService = $OAuthClientService;
        $this->onlineApplicationService = $onlineApplicationService;
        $this->middleware('auth');
    }

    public function index()
    {
        $userChosenApplications = $this->userService->getById(auth()->id())->OnlineApplications;
        $supportedApplications = $this->onlineApplicationService->getAll();//OnlineApplication::select('id', 'name', 'thumbnail')->get();

        //Remove applications from list that user has already chosen
        $supportedApplications = $supportedApplications->diff($userChosenApplications);

        return response()->view("index", ["supportedTypes" => $supportedApplications, "userChosenTypes" => $userChosenApplications]);
    }

    public function goToOnlineApplication(Request $request)
    {
    }

    public function addOnlineApplication(Request $request)
    {
        try {
            $this->userService->addOnlineApplicationToUser(auth()->id(), $request->id);
            return redirect('/');
        } catch (QueryException $ex) {
            return redirect('/')->withErrors(['Application already added']);
        }
    }

    public function getAll()
    {
        return response($this->onlineApplicationService->getAll());
    }

    public function getToken(Request $request)
    {
        $oAuthClient = $this->OAuthClientService->getByName($request['applicationType']);
        return Response()->json($this->OAuthClientService->getToken($request->input('code'), $oAuthClient));
    }


    public function OAuthLogin()
    {
        return redirect()->away($this->OAuthClientService->getLoginUrlByName('microsoft'));
    }
}
