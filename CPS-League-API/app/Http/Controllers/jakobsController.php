<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Mastery;

class jakobsController extends Controller
{
    public function index()
    {
        return view('jakob');
    }
}
