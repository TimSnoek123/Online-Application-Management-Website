<?php

namespace App\Services;

use App\OnlineApplication;
use DB;

class OnlineApplicationService
{
    public function __construct()
    {
        
    }
    public function getAll(){
        return OnlineApplication::all();
    }


}
