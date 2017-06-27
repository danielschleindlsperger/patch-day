<?php

namespace App\Http\Controllers;

use App\Http\Requests\Protocol\UpdateProtocol;
use App\Protocol;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @return \Illuminate\Http\Response
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
     * @param  int $id
     * @return \Illuminate\Http\Response
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  Protocol $protocol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Protocol $protocol)
    {
        $this->authorize('delete', $protocol);

        $patch_day = $protocol->patch_day;

        $today = Carbon::now()->endOfDay();

        if ($today->greaterThan(Carbon::parse($patch_day->date)->endOfDay())) {
            abort(422, 'Cannot delete patch-day');
        }

        $protocol->delete();
        return ['success' => true];
    }
}
