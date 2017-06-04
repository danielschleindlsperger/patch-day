<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\CreateProject;
use App\Http\Requests\Project\ProjectPatchDaySignup;
use App\Http\Requests\Project\UpdateProject;
use App\PatchDay;
use App\Project;
use App\Protocol;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Projects
 */
class ProjectController extends Controller
{
    /**
     * Display a listing of all projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Project::class);
        return Project::orderBy('name', 'ASC')->get();
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return mixed
     *
     * Return specified project
     */
    public function show(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $project->load('company', 'protocols');

        return $project;
    }

    /**
     * @param CreateProject $request
     * @return array
     *
     * Create new project
     */
    public function store(CreateProject $request)
    {
        $project = Project::create($request->all());

        if ($request->technologies) {
            $project->technologies()->attach($request->technologies);
        }

        return $project;
    }

    /**
     * @param UpdateProject $request
     * @param Project $project
     * @return array
     *
     * Update specified projects properties
     */
    public function update(UpdateProject $request, Project $project)
    {
        $project->update($request->except(['technologies']));

        if ($request->technologies) {
            $project->technologies()->attach($request->technologies);
        }

        return ['success' => true];
    }

    /**
     * @param Project $project
     * @return array
     *
     * Delete specified project
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();

        return ['success' => true];
    }

    /**
     * Sign a project up for a patch_day. Return the resulting protocol.
     *
     * @param ProjectPatchDaySignup $request
     * @param Project $project
     * @return mixed
     */
    public function projectSignup(ProjectPatchDaySignup $request, Project $project)
    {
        $patch_day = PatchDay::findOrFail($request->input('patch_day_id'));

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