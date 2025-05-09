<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use Illuminate\Http\Request;

class RefundsController extends Controller
{
    public function index()
    {
        $refunds = Refund::latest()->get();
        return view('refunds', compact('refunds'));
    }
}

{
    $refunds = Refund::latest()->get();
    return view('refunds', compact('refunds'));
}