<?php

namespace App\Http\Controllers;

use App\Models\Expertise;
use Illuminate\Http\Request;

class ExpertiseController extends Controller
{
    public function index()
    {
        $expertise = Expertise::all();
        return view('expertise', compact('expertise'));
    }
}