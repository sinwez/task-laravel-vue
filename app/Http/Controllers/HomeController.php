<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function welcome(){
//        Project::all();
        $projects = Auth::user()->projects()->get();
        return view('welcome',compact('projects'));
    }
}
