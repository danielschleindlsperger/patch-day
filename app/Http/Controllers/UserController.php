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
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load('company');
        return $user;
    }

    /**
     * Returns the logged in user.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function showMe(Request $request)
    {
        $user = $request->user();
        $user->load('company');
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUser $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        $user->update($request->all());
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
