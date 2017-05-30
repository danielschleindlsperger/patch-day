<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchDay\CreatePatchDay;
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
     * Display a listing of all patch-days.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', PatchDay::class);

        return PatchDay::orderBy('date', 'DESC')->get();
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
     * Create new patch-day.
     *
     * @param CreatePatchDay $request
     * @return array
     */
    public function store(CreatePatchDay $request)
    {
        $patch_day = PatchDay::create($request->all());

        return $patch_day;
    }

    /**
     * Update the specified patch-day.
     *
     * @param  UpdatePatchDay $request
     * @param  PatchDay $patchDay
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePatchDay $request, PatchDay $patchDay)
    {
        $patchDay->update($request->all());

        return ['updated' => true];
    }

    /**
     * Remove the specified patch-day.
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
