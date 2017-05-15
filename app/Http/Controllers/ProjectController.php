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

            if ($request->input('patch_day.technologies')) {
                $patchDay->technologies()->sync($request->input('patch_day.technologies'));
            }

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

        $project->update($request->except(['patch_day']));

        if ($request->input('patch_day')) {
            if ($project->patchDay) {
                if ($request->input('patch_day.technologies')) {
                    $project->patchDay->technologies()->sync
                    ($request->input('patch_day.technologies'));
                }
                $project->patchDay->update($request->patch_day);
            } else {
                abort(422, 'Projects PatchDay not found.');
            }
        }
        return ['success' => true];
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