<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function show($id)
    {
        $company = Company::find($id);

        if ($company) {
            $this->authorize('view', $company);
            return $company;
        } else {
            abort(404, 'Specified company was not found.');
        }
    }

    /**
     * @param $companyId
     * @return mixed
     *
     * return all Projects for the specified company
     */
    public function showCompanysProjects($companyId)
    {
        $company = Company::find($companyId);

        if ($company) {
            $this->authorize('view', $company);
            $projects = $company->projects;
            return $projects;
        } else {
            abort(404, 'Specified company not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $company = Company::find($id);

        if ($company) {
            $this->authorize('update', $company);
            $company->update($request->all());
            return ['success' => true];
        } else {
            abort(404, 'Specified company not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::find($id);

        if ($company) {
            $this->authorize('delete', $company);
            $company->delete();
            return ['success' => true];
        } else {
            abort(404, 'Specified company not found.');
        }
    }
}
