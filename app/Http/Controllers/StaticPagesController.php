<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function indexFAQ()
    {
        return view('pages.faq'); 
    }

    public function indexABOUT()
    {
        return view('pages.about'); 
    }

    public function indexCONTACT()
    {
        return view('pages.contact'); 
    }
}
