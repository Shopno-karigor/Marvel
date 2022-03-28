<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorHandler extends Controller
{
    //
    public function Error404(){
        abort(404);
    }
    public function Error500(){
        abort(500);
    }
}
