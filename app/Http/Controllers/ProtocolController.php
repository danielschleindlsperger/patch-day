<?php

namespace App\Http\Controllers;

use App\Http\Requests\Protocol\CreateProtocol;
use App\Http\Requests\Protocol\UpdateProtocol;
use App\Protocol;
use Illuminate\Http\Request;

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
    public function show($id)
    {
        $protocol = Protocol::with('patchDay', 'patchDay.project', 'patchDay.project.company')->find
        ($id);

        if ($protocol) {
            $this->authorize('view', $protocol);
            return $protocol;
        } else {
            abort(404, 'Specified protocol not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProtocol $request, $id)
    {
        $protocol = Protocol::find($id);

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
    public function destroy($id)
    {
        $protocol = Protocol::find($id);
        $this->authorize('delete', $protocol);
        $protocol->delete();

        return ['success' => true];
    }
}
