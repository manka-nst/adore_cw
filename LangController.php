<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LangController extends Controller
{
    public function setLang($lang)
    {
      //  dd($lang);
        app::setLocale('en');
        session::put('lang',$lang);
        return redirect()->back();


    }
}
