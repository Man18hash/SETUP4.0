<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function projects()
    {
        return view('projects');
    }

    public function fundedProjects()
    {
        return view('funded-projects');
    }

    public function refunds()
    {
        return view('refunds');
    }

    public function beneficiaries()
    {
        return view('beneficiaries');
    }

    public function setup()
    {
        return view('setup');
    }

    public function users()
    {
        return view('users');
    }
}

