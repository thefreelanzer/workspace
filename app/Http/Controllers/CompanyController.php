<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|dimensions:min_width=100,min_height=100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $logoPath = $request->file('logo')->store('public/logos');

        $company = new Company();
        $company->name = $request->name;
        $company->email = $request->email;
        $company->logo = $logoPath;
        $company->website = $request->website;
        $company->save();

        return redirect()->route('companies.index')->with('success', 'Company created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $company = Company::findOrFail($id);

        $company->name = $request->input('name');
        $company->email = $request->input('email');
        $company->website = $request->input('website');

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/logos');
            $company->logo = $logoPath;
        }
        $company->save();

        return redirect()->route('companies.index', $company->id)->with('success', 'Company details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Company deleted successfully.'
        ]);
    }

    public function getCompanies(Request $request)
    {
        $companies = Company::query();

        return DataTables::of($companies)
            ->addColumn('actions', function ($company) {
                $actions = '<button class="edit-btn" data-id="' . $company->id . '">Edit</button>';
                $actions .= ' <button class="delete-btn" data-id="' . $company->id . '">Delete</button>';
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
