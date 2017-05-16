<?php

namespace App\Http\Controllers;

use App\Http\Requests\Technology\CreateTechnology;
use App\Http\Requests\Technology\UpdateTechnology;
use App\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    /**
     * Display a listing of all technologies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $techs = Technology::orderBy('name', 'DESC')
            ->orderBy('version', 'DESC')
            ->get();
        return $techs;
    }

    /**
     * Store a newly created Technology in storage.
     *
     * @param  CreateTechnology $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTechnology $request)
    {
        Technology::create($request->all());
        return ['created' => true];
    }

    /**
     * Display Technology.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified Technology in storage.
     *
     * @param  UpdateTechnology $request
     * @param  Technology $technology
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(Technology $technology)
    {
        $this->authorize('delete', $technology);
        $technology->delete();

        return ['success' => true];
    }
}
