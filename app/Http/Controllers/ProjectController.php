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
     * If the user is a client, only show their companies projects.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return Project::orderBy('name', 'ASC')->get();
        } elseif (isset($user->company)) {
            return $user->company->projects()->get();
        } else {
            return [];
        }
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

        $project->makeVisible(['technology_history']);

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
     * @param int $id
     * @return array
     *
     * Update specified projects properties
     */
    public function update(UpdateProject $request, $id)
    {
        $project = Project::findOrFail($id);

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
}