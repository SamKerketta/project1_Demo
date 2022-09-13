<?php

namespace App\Http\Controllers;
use App\Models\student;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    function studentup(Request $req,$id)
    {
        $register=Student::find($id); 
        $register->name=$req->name;
        $register->age=$req->age;
        $register->email=$req->email;
        $register->save();
        if($register)
        {
            return["result"=>"data is updated"];
        }
        else
        {
            return["result"=>"not updated"];
        }
    }

}
