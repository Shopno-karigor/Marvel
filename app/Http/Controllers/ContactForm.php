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
            'email' => 'required|max:80|email:rfc,dns',
            'option' => 'required',
            'region' => 'required',
        ]);

        \Log::channel('contact')->info('Form submission count +1');

        
        return redirect()->back()->with('success', 'Form submission successful');
    }

    public function form_log(){
        $logfile=file(storage_path().'/logs/contact.log');
        $count = 0;
        $collection = [];
        foreach($logfile as $linenumber => $line){
            $count=$count+1;
            $collection[]=array('line' => $linenumber, 'content' => htmlspecialchars($line));
        }
        //return $count;
        dd($collection);
    }

}
