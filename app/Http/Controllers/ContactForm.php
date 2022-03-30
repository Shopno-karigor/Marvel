<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Validation\Rule;
use DB;

class ContactForm extends Controller
{
    //
    public function index(){
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

    public function show(){
        //$data=DB::table('form_submissions')->get();
        $data = Post::with( relation: 'form_submissions')
        ->when( $request->has(key:'archive'), function($query){
            $query->onlyTrashed();
        })
        ->get();
        return view('project.form_data', compact('data'));
    }

    public function update_show($id){
        $data=DB::table('form_submissions')->where('id',$id)->get();
        return view('project.form_update', compact('data'));
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'name' => 'bail|required|max:255',
            'email' => [
                'bail',
                'required',
                'max:80',
                'email:rfc,dns',
                Rule::unique('form_submissions', 'email')->ignore($id)
            ],
            'option' => 'required',
            'region' => 'required',
            'image' => 'image|mimes:png,jpg',
        ]);
        // dd($request->all());
        $max=DB::table('form_submissions')->where('id',$id)->get();
        $max_id=$id;     
        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi')."-".$max_id.$file->getClientOriginalName();
            if($file-> move(public_path('Images'), $filename)){
                $request['image']= $filename;
            }else{
                return redirect()->back()->with('error', 'Image upload failed');
            }
        }else{
            $filename=$max[0]->image;
        }
        $data=array(
            'name' => $request->name,
            'email' => $request->email,
            'option' => $request->option,
            'region' => $request->region,
            'message' => $request->message,
            'image' => $filename,
            'updated_at' => date("Y-m-d")." ".date("h:i:s")
        );
        $result=DB::table('form_submissions')->where('id', $id)->update($data);
        \Log::channel('contact')->info('Form update count +1');
        if ($result){
            return redirect()->back()->with('success', 'Form update successful');
        }else{
            return redirect()->back()->with('error', 'Form update failed');
        }
    }

    
    public function delete($id){
        $data=array(
            'deleted_at' => date("Y-m-d")." ".date("h:i:s")
        );
        $result=DB::table('form_submissions')->where('id', $id)->update($data);
        \Log::channel('contact')->info('Form deleted count -1');
        if ($result){
            return redirect()->back()->with('success', 'Data delete successful');
        }else{
            return redirect()->back()->with('error', 'Data delete failed');
        }
        
    }

    public function secret_page(){

        return view('project.secret_page');
    }

}
