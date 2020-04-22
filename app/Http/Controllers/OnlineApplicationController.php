<?php

namespace App\Http\Controllers;

use App\OnlineApplication;
use App\Services\OAuthClientService;
use App\Services\OnlineApplicationService;
use App\Services\userService;
use Illuminate\Http\Request;
use App\User;
use Exception;

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
        $userChosenApplications = $this->userService->getById(auth()->id())->onlineApplications;
        $supportedApplications = $this->onlineApplicationService->getAll();//OnlineApplication::select('id', 'name', 'thumbnail')->get();

        return response()->view("index", ["supportedTypes" => $supportedApplications, "userChosenTypes" => $userChosenApplications]);
    }

    public function goToOnlineApplication(Request $request)
    {
    }

    public function addOnlineApplication(Request $request)
    {
        $chosenApplication = OnlineApplication::find($request->id);
        try {
            User::whereId(auth()->id())->first()->OnlineApplications()->save($chosenApplication);
            return redirect('/');
        } catch (Exception $ex) {
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
