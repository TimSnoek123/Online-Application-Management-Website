<?php

namespace App\Http\Controllers;

use App\SourceCompany;

class IndexController extends Controller
{
   public function index(){
    //    $test = SourceCompany::with('OAuthClient')->first();

    //    return response()->json($test);
       return view('index');
   }
}
