<?php

namespace himekawa\Http\Controllers;

class HomeController extends Controller
{
    public function faq()
    {
        return view('frontend.faq');
    }

    public function about()
    {
    }
}
