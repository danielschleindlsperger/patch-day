<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUser;
use App\Http\Requests\User\UpdateUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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
    public function store(CreateUser $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
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
        if ($id === 'me') {
            if ($user = Auth::user()) {
                return $user;
            } else {
                abort(404, 'No authenticated user found.');
            }
        } else {
            if ($user = User::find($id)) {
                return $user;
            } else {
                abort(404, 'User not found.');
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($request->all());
            return ['updated' => true];
        } else {
            abort(404);
        }
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
