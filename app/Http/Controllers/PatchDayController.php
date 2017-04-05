<?php

namespace App\Http\Controllers;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'cost' => 'numeric',
            'active' => 'boolean',
            'start_date' => 'required|date',
            'project_id' => 'required|numeric',
        ]);

        $project = Project::find($request->project_id);

        if ($project) {
            $patchDay = PatchDay::create([
                'cost' => $request->cost,
                'start_date' => $request->start_date,
                'active' => $request->active,
            ]);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
