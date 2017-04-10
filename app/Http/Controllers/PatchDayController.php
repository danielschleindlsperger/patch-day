<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchDay\CreatePatchDay;
use App\Http\Requests\PatchDay\UpdatePatchDay;
use App\PatchDay;
use App\Project;
use Illuminate\Http\Request;

class PatchDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //TODO: only return PatchDays if user is an admin
        return PatchDay::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePatchDay $request)
    {
        $project = Project::find($request->project_id);

        if ($project) {

            $patchDay = PatchDay::create($request->all());
            $patchDay->project()->associate($project);
            $patchDay->save();

            return ['created' => true];
        } else {
            abort(404, 'Project not found');
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
        $patchDay = PatchDay::find($id);

        if ($patchDay) {
            return $patchDay;
        } else {
            abort(404, 'PatchDay not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePatchDay $request, $id)
    {
        $patchDay = PatchDay::find($id);
        $patchDay->update($request->all());

        return ['updated' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patchDay = PatchDay::find($id);
        $patchDay->delete();

        return ['success' => true];
    }

    /**
     * @param $patchDayId
     * @return mixed
     *
     * show the PatchDay's protocols
     */
    public function showPatchDaysProtocols($patchDayId)
    {
        //TODO: only return the protocols when the user is an admin or
        // when the patchDay belongs to the user's company

        $patchDay = PatchDay::find($patchDayId);

        if ($patchDay) {
            $protocols = $patchDay->protocols;
            return $protocols;
        } else {
            abort(404, 'Specified PatchDay not found.');
        }
    }
}
