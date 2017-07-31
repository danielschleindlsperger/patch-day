<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\Company\CreateCompany;
use App\Http\Requests\Company\UpdateCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

/**
 * @resource Companies
 */
class CompanyController extends Controller
{
    /**
     * Display a listing of all companies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Company::class);

        return Company::orderBy('name', 'ASC')->get();
    }

    /**
     * Create a new company.
     *
     * @param  CreateCompany $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCompany $request)
    {
        $attributes = [
            'name' => $request->name,
        ];

        if ($request->file('logo')) {
            $attributes['logo'] = $this->storeLogo($request);
        }

        $company = Company::create($attributes);

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
        $attributes = [];

        if ($request->file('logo')) {
            $attributes['logo'] = $this->storeLogo($request, $company->name);
        }

        if ($request->name) {
            $attributes['name'] = $request->name;
        }

        $company->update($attributes);

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

    /**
     * Store logo and return the saved path.
     *
     * @param Request $request
     * @return false|string
     */
    private function storeLogo(Request $request, $name = null)
    {
        $name = is_null($name) ? $request->name : $name;

        $ext = '.' . $request->file('logo')->getClientOriginalExtension();
        $timestamp = (new \DateTime())->getTimestamp();
        $filename = str_slug($name) . $timestamp . $ext;
        $path = $request->file('logo')
            ->storeAs('logos', $filename, ['disk' => 'public']);

        return $path;
    }
}
