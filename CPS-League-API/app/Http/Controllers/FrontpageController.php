<?php

namespace App\Http\Controllers;

class FrontpageController extends Controller
{
    public function index()
    {
       return view('frontpage');
    }
}
