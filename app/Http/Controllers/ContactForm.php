<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactForm extends Controller
{
    //
    public function show(){
        return view('project.form');
    }

    public function submit(Request $request){
        dd($request->all());
    }
}
