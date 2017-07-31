<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUser;
use App\Http\Requests\User\UpdateUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Users
 * In order to view any content a visitor needs to have a registered User
 * account. Users with the role of 'client' can view some resources that
 * belong to their company. Users with the role of 'admin' can view all
 * resources and can edit them aswell.
 */
class UserController extends Controller
{
    /**
     * Display a listing of all users with their companies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', User::class);

        $users = User::orderBy('id', 'DESC')->with('company')->get();

        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateUser $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUser $request)
    {
        $request->merge(['password' => bcrypt($request->password)]);
        $user = User::create($request->all());
        return $user;
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
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return ['sucess' => true];
    }
}
