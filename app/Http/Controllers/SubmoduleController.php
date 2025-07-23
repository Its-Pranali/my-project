<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubmoduleController extends Controller
{
    public function submodule(){
        return view('admin/Submodule');
    }
}
