<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchDay\CreatePatchDay;
use App\Http\Requests\PatchDay\UpdatePatchDay;
use App\PatchDay;

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
        return PatchDay::orderBy('date', 'DESC')->get();
    }

    /**
     * Display a listing of all upcoming patch-days.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming()
    {
        $patch_days = PatchDay::where('status', '!=', 'done')
            ->orderBy('date', 'ASC')->get();

        return $patch_days;
    }

    /**
     * Display the specified patch day.
     *
     * @param  PatchDay $patchDay
     * @return PatchDay
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(PatchDay $patchDay)
    {
        $this->authorize('view', $patchDay);

        if (request('todo')) {
            // only load protocols that are not done
            $patchDay->load(['protocols' => function ($query) {
                $query->where('protocols.done', '!=', true);
            }, 'protocols.project', 'protocols.project.company']);
        } else {
            $patchDay->load('protocols', 'protocols.project', 'protocols.project.company');
        }

        return $patchDay;
    }

    /**
     * Create new patch day.
     *
     * @param CreatePatchDay $request
     * @return array
     */
    public function store(CreatePatchDay $request)
    {
        return PatchDay::create($request->all());
    }

    /**
     * Update the specified patch day.
     *
     * @param  UpdatePatchDay $request
     * @param  PatchDay       $patchDay
     * @return array|\Illuminate\Http\Response
     */
    public function update(UpdatePatchDay $request, PatchDay $patchDay)
    {
        $patchDay->update($request->all());

        return ['updated' => true];
    }

    /**
     * Remove the specified patch day.
     *
     * @param  PatchDay $patchDay
     * @return array|\Illuminate\Http\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(PatchDay $patchDay)
    {
        $this->authorize('delete', $patchDay);
        $patchDay->delete();

        return ['success' => true];
    }
}
