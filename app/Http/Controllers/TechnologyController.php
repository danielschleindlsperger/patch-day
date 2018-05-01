<?php

namespace App\Http\Controllers;

use App\Http\Requests\Technology\CreateTechnology;
use App\Http\Requests\Technology\UpdateTechnology;
use App\Technology;

/**
 * @resource Technologies
 *
 * Technologies are frameworks, languages and all other software used on the
 * front- and backend. Examples are 'Laravel', 'php' or 'Apache'.
 * Technologies also include versions.
 */
class TechnologyController extends Controller
{
    /**
     * Display a listing of all technologies.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', Technology::class);

        return Technology::orderByRaw('LOWER(name) ASC')
            ->orderByRAW('LOWER(version) DESC')
            ->get();
    }

    /**
     * Get all versions that exist for the technology with the specified name.
     *
     * @param $name
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function showVersionsForTech($name)
    {
        $this->authorize('index', Technology::class);

        return Technology::where('name', $name)
            ->orderByRAW('LOWER(version) DESC')
            ->get();
    }

    /**
     * Store a newly created Technology in storage.
     *
     * @param  CreateTechnology $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTechnology $request)
    {
        return Technology::create($request->all());
    }

    /**
     * Update the specified Technology in storage.
     *
     * @param  UpdateTechnology $request
     * @param  Technology       $technology
     * @return array|\Illuminate\Http\Response
     */
    public function update(UpdateTechnology $request, Technology $technology)
    {
        $technology->update($request->all());
        return ['updated' => true];
    }

    /**
     * Remove the Technology from storage.
     *
     * @param  Technology $technology
     * @return array|\Illuminate\Http\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Technology $technology)
    {
        $this->authorize('delete', $technology);
        $technology->delete();

        return ['success' => true];
    }
}
