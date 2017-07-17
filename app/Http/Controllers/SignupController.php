<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ProjectPatchDaySignup;
use App\PatchDay;
use App\Project;
use App\Protocol;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    /**
     * Sign a project up for a patch_day. Return the resulting protocol.
     *
     * @param ProjectPatchDaySignup $request
     * @param Project $project
     * @return mixed
     */
    public function signup(ProjectPatchDaySignup $request, Project $project)
    {
        $patch_day = PatchDay::findOrFail(request('patch_day_id'));

        if ($this->patchDayIsOver($patch_day)) {
            abort(422, 'Too late to sign up for this PatchDay.');
        }

        if ($this->projectIsSignedUpForPatchDay($project, $patch_day)) {
            abort(422, 'Already signed up for PatchDay.');
        }

        $protocol = Protocol::create([
            'project_id' => $project->id,
            'patch_day_id' => $patch_day->id,
        ]);

        return $protocol;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel(Project $project)
    {
        $patch_day = PatchDay::findOrFail(request('patch_day_id'));

        $protocol = Protocol::where('project_id', '=', $project->id)
                        ->where('patch_day_id', '=', $patch_day->id)
                        ->firstOrFail();

        $this->authorize('delete', $protocol);

        if ($this->patchDayIsOver($patch_day)) {
            abort(422, 'Too late to delete this PatchDay.');
        }

        $protocol->delete();

        return ['success' => true];
    }

    /**
     * The PatchDays a project is registered to.
     * @param Project $project
     * @return Collection
     */
    public function registeredPatchDays(Project $project)
    {
        return $project->patchDays();
    }

    /**
     * The PatchDays a project can still sign up for.
     *
     * @param Project $project
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function possibleSignups(Project $project)
    {
        $patch_days = PatchDay::all();
        $keys = $project->patchDays()->pluck('id')->toArray();

        $patch_days = $patch_days->filter(function ($patch_day) use ($keys) {
            return !in_array($patch_day->id, $keys) &&
                !$this->patchDayIsOver($patch_day);
        })->values();

        return $patch_days;
    }

    /**
     * @param PatchDay $patch_day
     * @return bool
     */
    private function patchDayIsOver($patch_day)
    {
        $today = Carbon::now()->endOfDay();
        $date = Carbon::parse($patch_day->date)->endOfDay();

        return !$date->greaterThan($today) || $patch_day->done;
    }

    /**
     * @param Project $project
     * @param PatchDay $patchDay
     * @return bool
     */
    private function projectIsSignedUpForPatchDay($project, $patchDay)
    {
        $keys = $project->patchDays()->pluck('id')->toArray();

        return in_array($patchDay->id, $keys);
    }
}
