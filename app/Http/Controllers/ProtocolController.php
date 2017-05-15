<?php

namespace App\Http\Controllers;

use App\Http\Requests\Protocol\CreateProtocol;
use App\Http\Requests\Protocol\UpdateProtocol;
use App\Protocol;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProtocol $request)
    {
        $this->authorize('create', Protocol::class);
        Protocol::create($request->all());
        return ['success' => true];
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Protocol $protocol)
    {
        $protocol->load(['patchDay', 'patchDay.project', 'patchDay.project.company']);
        $this->authorize('view', $protocol);
        return $protocol;
    }

    /**
     * Return upcoming patch-days for admins
     *
     * @param Request $request
     * @return mixed
     * @throws AuthenticationException
     */
    public function showUpcoming(Request $request)
    {
        $limit = $request->limit ?: 5;
        if (Auth::user()->isAdmin()) {
            $protocols = Protocol::with('patchDay', 'patchDay.project')
                ->where('done', false)
                ->orderBy('due_date', 'ASC')
                ->take($limit)
                ->get();
            return $protocols;
        } else {
            throw new AuthenticationException();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProtocol $request, Protocol $protocol)
    {
        $this->authorize('update', $protocol);
        $protocol->update($request->all());
        return ['updated' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Protocol $protocol)
    {
        $this->authorize('delete', $protocol);
        $protocol->delete();
        return ['success' => true];
    }
}
