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
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:80|email',
            'option' => 'required',
            'region' => 'required',
        ]);
        return redirect()->back()->with('success', 'Form submission successful');
    }
}
