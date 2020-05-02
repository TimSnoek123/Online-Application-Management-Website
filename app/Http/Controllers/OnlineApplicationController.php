<?php

namespace App\Http\Controllers;

use App\CommandLink;
use App\OnlineApplication;
use App\Services\CommandLinkService;
use App\Services\OAuthClientService;
use App\Services\OnlineApplicationService;
use App\Services\userService;
use Illuminate\Http\Request;
use App\User;
use Exception;
use Illuminate\Database\QueryException;

class OnlineApplicationController extends Controller
{
    private $commandLinkService;
    private $onlineApplicationService;
    private $OAuthClientService;
    private $userService;


    public function __construct(OnlineApplicationService $onlineApplicationService, OAuthClientService $OAuthClientService, UserService $userService, CommandLinkService $commandLinkService)
    {
        $this->commandLinkService = $commandLinkService;
        $this->userService = $userService;
        $this->OAuthClientService = $OAuthClientService;
        $this->onlineApplicationService = $onlineApplicationService;
        $this->middleware('auth');
    }

    public function index()
    {
        $userChosenApplications = $this->userService->getById(auth()->id())->OnlineApplications;
        $supportedApplications = $this->onlineApplicationService->getAll(); //OnlineApplication::select('id', 'name', 'thumbnail')->get();

        //Remove applications from list that user has already chosen
        $supportedApplications = $supportedApplications->diff($userChosenApplications);

        return response()->view("applications", ["supportedTypes" => $supportedApplications, "userChosenTypes" => $userChosenApplications]);
    }

    public function goToOnlineApplication(Request $request)
    {
        //User hasn't logged in with this organization yet
        if (!$request->hasCookie($request['sourceCompany']) && $request->input('redirectFromLogin') == null) {

            $request->session()->flash('applicationReturnUrl', $request->path());
            return $this->doOnlineApplicationLogin($request);
        }

        return view('applications/' . $request['sourceCompany'] . '/' . $request['application']);
        // return response($request->cookie($request['sourceCompany']));
    }


    public function getAll()
    {
        return response($this->onlineApplicationService->getAll());
    }

    public function getTokenFromCodeForApplication(Request $request)
    {
        $oAuthClient = $this->OAuthClientService->getBySourceCompany($request['sourceCompany']);
        $fullToken = $this->OAuthClientService->getToken($request->input('code'), $oAuthClient);
        $accessToken = json_encode($fullToken['access_token']);


        //Send back to go to online application 
        return redirect($request->session()->get('applicationReturnUrl'))->withCookie(cookie($request['sourceCompany'], $accessToken), $fullToken['expires_in']);
    }


    public function doOnlineApplicationLogin(Request $request)
    {
        return redirect()->away($this->OAuthClientService->getLoginUrlByName($request['sourceCompany']));
    }

    public function getUnencryptedOAuthToken(Request $request)
    {
        return response()->json($request->cookie($request->input('sourceCompany')));
    }

    public function executeApplicationCommand(Request $request){
        
        $value = $this->commandLinkService->executeCommand('GetOneDriveFilesByFolder', $request->all(), $request->cookie('microsoft'));
        return response()->json($value);
    }
}
