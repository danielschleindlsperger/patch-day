<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\CreateProject;
use App\Http\Requests\Project\UpdateProject;
use App\PatchDay;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @param $id
     * @return mixed
     *
     * Return specified project
     */
    public function show(Request $request, $id)
    {
        $project = Project::with([
            'company', 'patchDay', 'patchDay.protocols'
        ])->find($id);

        if ($project) {
            $this->authorize('view', $project);
            return $project;
        } else {
            abort(404, 'Project not found.');
        }
    }

    /**
     * @param CreateProject $request
     * @return array
     *
     * Create new project
     */
    public function store(CreateProject $request)
    {
        $project = Project::create($request->except(['patch_day']));

        if ($project) {
            $fields = array_merge($request->patch_day, [
                'project_id' => $project->id,
            ]);
            $patchDay = PatchDay::create($fields);
            return ['created' => true];
        }
    }

    /**
     * @param UpdateProject $request
     * @param $id
     * @return array
     *
     * Update specified projects properties
     */
    public function update(UpdateProject $request, $id)
    {
        $project = Project::with('patchDay')->find($id);

        if ($project) {
            $project->update($request->except(['patch_day']));

            if ($request->patch_day) {
                if ($project->patchDay) {
                    $project->patchDay->update($request->patch_day);
                } else {
                    abort(404, 'Projects PatchDay not found.');
                }
            }
            return ['success' => true];
        } else {
            abort(404, 'Project not found.');
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     *
     * Delete specified project
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        $this->authorize('delete', $project);
        $project->delete();

        return ['success' => true];
    }
}