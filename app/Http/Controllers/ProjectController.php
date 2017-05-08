<?php

namespace App\Http\Controllers;

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
     * @param $projectId
     * @return mixed
     *
     * return all PatchDays for the specified Project
     */
    public function showProjectsProtocols($projectId)
    {
        $project = Project::with('patchDay', 'patchDay.protocols')->find
        ($projectId);

        if ($project) {
            $this->authorize('view', $project);
            $protocols = $project->patchDay->protocols()->get();
            return $protocols;
        } else {
            abort(404, 'Specified project not found');
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
        $project = Project::with(['company', 'patchDay'])->find($id);

        if ($project) {
            $this->authorize('view', $project);
            return $project;
        } else {
            abort(404, 'Project not found.');
        }
    }

    /**
     * @param Request $request
     * @return array
     *
     * Create new project
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $this->validate($request, [
            'name' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);

        $project = Project::create([
            'name' => $request->name,
        ]);

        if ($project) {
            return ['created' => true];
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     *
     * Update specified projects properties
     */
    public function update(Request $request, $id)
    {
        $project = Project::with('patchDay')->find($id);

        $this->authorize('update', $project);

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