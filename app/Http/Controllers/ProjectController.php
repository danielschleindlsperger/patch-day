<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\CreateProject;
use App\Http\Requests\Project\UpdateProject;
use App\Project;
use App\Technology;
use Illuminate\Http\Request;

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
     * @return array|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return Project::orderBy('name', 'ASC')->get();
        }

        if (isset($user->company)) {
            return $user->company->projects()->get();
        }

        return [];
    }

    /**
     * @param Project $project
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * Return specified project
     */
    public function show(Project $project)
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

            $tech = [];

            foreach ($request->technologies as $technology) {
                $tech[$technology] = ['action' => 'default'];
            }

            $project->technologies()->attach($tech);
        }

        return $project;
    }

    /**
     * @param UpdateProject $request
     * @param int           $id
     * @return array
     *
     * Update specified projects properties
     */
    public function update(UpdateProject $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->update($request->except(['technologies']));

        if ($request->technologies) {

            $project->technologies()->where('action', '=', 'default')
                ->detach();

            $tech = [];

            foreach ($request->technologies as $technology) {
                $tech[$technology] = ['action' => 'default'];
            }

            $project->technologies()->attach($tech);
        }

        return ['success' => true];
    }

    /**
     * @param Project $project
     * @return array
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * Delete specified project
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();

        return ['success' => true];
    }

    public function deleteTech(Project $project)
    {
        $this->authorize('deleteProjectTech', $project);

        $tech = Technology::findOrFail(request('tech'));

        $project->technologies()->attach($tech->id, [
            'action' => 'deleted',
        ]);

        return ['success' => true];
    }
}