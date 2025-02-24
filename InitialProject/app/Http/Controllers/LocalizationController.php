<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResearchGroup;
use App\Models\ResearchProject;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;



class LocalizationController extends Controller
{
    public function index()
    {
        $resp = ResearchGroup:: all();
        return view('welcome',compact('resp'));
       // return view('welcome');
    }
    public function switchLang($lang)
    {
        $languages = Config::get('languages');
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('applocale', $lang);
            App::setLocale($lang);
        }
        return redirect()->back();
    }
}



