<?php

namespace App\Services;

use App\User;
use App\OnlineApplication;

class UserService
{
  public function getById($id)
  {
    return User::find($id);
  }

  public function addOnlineApplicationToUser($userId, $applicationId)
  {
    $chosenApplication = OnlineApplication::find($applicationId);

    User::whereId($userId)->first()->OnlineApplications()->save($chosenApplication);
  }

  public function createUser(User $user)
  {
    $user->save();
  }
}
