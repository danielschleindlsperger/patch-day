<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchDay\CreateProject;
use App\Http\Requests\PatchDay\UpdatePatchDay;
use App\PatchDay;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatchDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user() && Auth::user()->isAdmin()) {
            return PatchDay::all();
        } else {
            abort(403, 'Not authorized.');
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
            $this->authorize('view', $patchDay);
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

        if ($request->technologies) {
            $patchDay->technologies()->sync($request->technologies);
        }

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
        $this->authorize('delete', $patchDay);
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
        $patchDay = PatchDay::find($patchDayId);

        if ($patchDay) {
            $this->authorize('view', $patchDay);
            $protocols = $patchDay->protocols;
            return $protocols;
        } else {
            abort(404, 'Specified PatchDay not found.');
        }
    }
}
