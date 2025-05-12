<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class graphController extends Controller
{
    public function index() {
        return view('graph');
    }

    public function LineChart()
    {
        return "hi There";
    }

}
