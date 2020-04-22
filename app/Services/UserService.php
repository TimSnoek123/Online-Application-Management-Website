<?php

namespace App\Services;
use App\User;
use App\OnlineApplication;
use Exception;

class UserService
{

    public function __construct()
    {
    }


    public function getById($id){
      return User::find($id);
    }

    public function addOnlineApplicationToUser($userId, $applicationId){
      $chosenApplication = OnlineApplication::find($applicationId);

        User::whereId($userId)->first()->OnlineApplications()->save($chosenApplication);
    }
}
