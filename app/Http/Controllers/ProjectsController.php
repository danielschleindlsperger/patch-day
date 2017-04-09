<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     *
     * Return all projects
     */
    public function index()
    {
        //TODO: only return all projects when the user is an admin or
        // only return the users company's projects
        return Project::all();
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
        $project = Project::find($id);

        if ($project) {
            return $project;
        } else {
            abort(404, 'Project not found.');
        }
    }

    /**
     * @param Request $request
     * @return array
     *
     * Create new Project
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
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
     * Update specified project's properties
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if ($project) {
            $project->name = $request->name;
            $project->save();
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
    public function destroy(Request $request, $id)
    {
        $project = Project::find($id);
        $project->delete();

        return ['success' => true];
    }
}