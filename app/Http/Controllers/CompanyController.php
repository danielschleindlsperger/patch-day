<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Companies
 */
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user() && Auth::user()->isAdmin()) {
            return Company::all();
        } else {
            abort(403, 'Not authorized.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Company::class);

        $this->validate($request, [
            'name' => 'required',
        ]);

        $company = Company::create([
            'name' => $request->name,
        ]);

        if ($company) {
            return ['created' => true];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $this->authorize('view', $company);
        $company->load('projects', 'projects.patchDay');
        return $company;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);
        $company->update($request->all());
        return ['success' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);
        $company->delete();
        return ['success' => true];
    }
}
