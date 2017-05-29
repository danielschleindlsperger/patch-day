<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\Company\CreateCompany;
use App\Http\Requests\Company\UpdateCompany;
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
     * Create a new company.
     *
     * @param  CreateCompany $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCompany $request)
    {
        $company = Company::create($request->all());

        return $company;
    }

    /**
     * Display the specified company with its projects.
     *
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $this->authorize('view', $company);
        $company->load('projects');
        return $company;
    }

    /**
     * Update the specified company.
     *
     * @param  UpdateCompany $request
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompany $request, Company $company)
    {
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
