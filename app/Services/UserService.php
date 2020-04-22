<?php

namespace App\Services;
use App\User;

class UserService
{

    public function __construct()
    {
    }


    public function getById($id){
      return User::find($id);
    }
}
