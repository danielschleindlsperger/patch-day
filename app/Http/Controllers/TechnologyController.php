<?php

namespace App\Http\Controllers;

use App\Http\Requests\Technology\CreateTechnology;
use App\Http\Requests\Technology\UpdateTechnology;
use App\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
