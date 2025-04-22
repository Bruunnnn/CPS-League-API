<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class petersController extends Controller
{
    public function index() {
        return view('peters');
    }
}
