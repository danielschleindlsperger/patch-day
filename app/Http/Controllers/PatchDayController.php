<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchDay\CreateProject;
use App\Http\Requests\PatchDay\UpdatePatchDay;
use App\PatchDay;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\ApacheRequest;

/**
 * @resource PatchDays
 * PatchDays are an extension of Projects with information about the
 * recurring events that are then stored in the form of Protocols.
 */
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
     * @param  PatchDay $patchDay
     * @return \Illuminate\Http\Response
     */
    public function show(PatchDay $patchDay)
    {
        $this->authorize('view', $patchDay);
        return $patchDay;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePatchDay $request
     * @param  PatchDay $patchDay
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePatchDay $request, PatchDay $patchDay)
    {
        if ($request->technologies) {
            $patchDay->technologies()->sync($request->technologies);
        }

        $patchDay->update($request->all());

        return ['updated' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PatchDay $patchDay
     * @return \Illuminate\Http\Response
     */
    public function destroy(PatchDay $patchDay)
    {
        $this->authorize('delete', $patchDay);
        $patchDay->delete();

        return ['success' => true];
    }
}
