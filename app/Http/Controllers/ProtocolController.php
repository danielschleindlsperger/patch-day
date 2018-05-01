<?php

namespace App\Http\Controllers;

use App\Http\Requests\Protocol\UpdateProtocol;
use App\Protocol;

/**
 * @resource Protocols
 *
 * Protocols are the recurring Events for PatchDays in which the Projects are
 * updated.
 */
class ProtocolController extends Controller
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
     * Display the specified resource.
     *
     * @param  Protocol $protocol
     * @return Protocol|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Protocol $protocol)
    {
        $this->authorize('view', $protocol);

        return $protocol;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProtocol $request
     * @param  int            $id
     * @return array|\Illuminate\Http\Response
     */
    public function update(UpdateProtocol $request, $id)
    {
        $protocol = Protocol::findOrFail($id);

        if ($request->input('technology_updates')) {
            $protocol->syncTechnologies($request->technology_updates);
        }

        $protocol->update($request->except(['technology_updates']));

        $protocol->update($request->all());

        return ['updated' => true];
    }
}
