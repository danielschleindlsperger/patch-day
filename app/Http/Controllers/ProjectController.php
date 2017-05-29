<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\CreateProject;
use App\Http\Requests\Project\UpdateProject;
use App\PatchDay;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Projects
 */
class ProjectController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     *
     * Return all projects
     */
    public function index(Request $request)
    {
        if (Auth::user() && Auth::user()->isAdmin()) {
            return Project::all();
        } else {
            abort(403, 'Not authorized.');
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
        $project->load(['company', 'patchDay', 'patchDay.protocols', 'patchDay.technologies']);
        $this->authorize('view', $project);
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

        return ['created' => true];
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
}