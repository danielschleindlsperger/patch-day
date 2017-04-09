<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //TODO: only return companies if user is an admin
        return Company::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::find($id);

        if ($company) {
            return $company;
        } else {
            abort(404, 'Specified company was not found.');
        }
    }

    /**
     * @param $companyId
     * @return mixed
     *
     * return all Projects for the specified Company
     */
    public function showCompanysProjects($companyId)
    {
        //TODO: only return the Projects when the user is an admin or
        // when they belong to the user's company

        $company = Company::find($companyId);

        if ($company) {
            $projects = $company->projects;
            return $projects;
        } else {
            abort(404, 'Specified company not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //TODO: only return the Projects when the user is an admin or
        // when they belong to the user's company

        $company = Company::find($id);

        if ($company) {
            $company->update($request->all());
            return ['success' => true];
        } else {
            abort(404, 'Specified company not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
