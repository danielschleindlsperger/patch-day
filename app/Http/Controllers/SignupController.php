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

        $today = Carbon::now()->endOfDay();

        if ($today->greaterThan(Carbon::parse($patch_day->date))) {
            abort(422, 'Cannot sign up for past patch-day');
        }

        $protocol = Protocol::create([
            'project_id' => $project->id,
            'patch_day_id' => $patch_day->id,
        ]);

        return $protocol;
    }
}
