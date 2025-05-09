<?php

namespace App\Http\Controllers;

use App\Models\User; // Ensure you have a User model
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        // Fetch all users with the fields: id, name, email
        $users = User::select('id', 'name', 'email')->get();

        // Pass the data to the 'users' view
        return view('users', compact('users'));
    }
}
