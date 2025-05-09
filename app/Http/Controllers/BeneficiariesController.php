<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;

class BeneficiariesController extends Controller
{
    /**
     * Display a listing of the beneficiaries with search and filter functionality.
     */
    public function index(Request $request)
    {
        $query = Beneficiary::query();

        // Apply search filtering (search by name, TIN, or email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'LIKE', "%{$search}%")
                  ->orWhere('middlename', 'LIKE', "%{$search}%")
                  ->orWhere('lastname', 'LIKE', "%{$search}%")
                  ->orWhere('tin', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by sector, category, and province if provided
        if ($request->filled('filter_sector')) {
            $query->where('sector', $request->input('filter_sector'));
        }
        if ($request->filled('filter_category')) {
            $query->where('category', $request->input('filter_category'));
        }
        if ($request->filled('filter_province')) {
            $query->where('province', $request->input('filter_province'));
        }

        $beneficiaries = $query->get();

        return view('beneficiaries', compact('beneficiaries'));
    }

    /**
     * Store a newly created beneficiary in storage.
     */
    public function store(Request $request)
    {
        // Validate inputs
        $validatedData = $request->validate([
            'firmname'    => 'required|string|max:255',
            'firstname'   => 'required|string|max:255',
            'middlename'  => 'nullable|string|max:255',
            'lastname'    => 'required|string|max:255',
            'suffix'      => 'nullable|string|max:50',
            'tin'         => 'required|string|max:50',
            'address'     => 'required|string',
            'province'    => 'required|string|max:255',
            'tel_no'      => 'nullable|string|max:50',
            'contact_no'  => 'required|string|max:50',
            'sector'      => 'required|string|max:255',
            'category'    => 'required|string|max:50',
            'email'       => 'required|email|max:255',
        ]);

        // If a custom sector was provided, override the sector field
        if ($request->filled('sector_custom')) {
            $validatedData['sector'] = $request->input('sector_custom');
        }

        Beneficiary::create($validatedData);

        return redirect()->back()->with('success', 'Beneficiary added successfully.');
    }
}
