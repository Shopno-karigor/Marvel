<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ContactForm extends Controller
{
    //
    public function show(){
        return view('project.form');
    }

    public function submit(Request $request){
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:80|email:rfc,dns|unique:form_submissions,email',
            'option' => 'required',
            'region' => 'required',
            'image' => 'required|image|mimes:png,jpg',
        ]);
        //dd($request->all());
        $max=DB::table('form_submissions')->get();
        if(count($max)==0){
            $max_id=1;
        }else{
            $max_id=count($max)+1;
        }      
        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi')."-".$max_id.$file->getClientOriginalName();
            if($file-> move(public_path('Images'), $filename)){
                $request['image']= $filename;
            }else{
                return redirect()->back()->with('error', 'Image upload failed');
            }
        }
        $data=array(
            'name' => $request->name,
            'email' => $request->email,
            'option' => $request->option,
            'region' => $request->region,
            'message' => $request->message,
            'image' => $filename,
            'created_at' => date("Y-m-d")." ".date("h:i:s")
        );
        $result=DB::table('form_submissions')->insert($data);
        \Log::channel('contact')->info('Form submission count +1');
        if ($result){
            return redirect()->back()->with('success', 'Form submission successful');
        }else{
            return redirect()->back()->with('error', 'Form submission failed');
        }
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

    public function secret_page(){
        return view('project.secret_page');
    }

}
