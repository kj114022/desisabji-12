<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testcontroller extends Controller
{
     public function test()
     {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }
}

