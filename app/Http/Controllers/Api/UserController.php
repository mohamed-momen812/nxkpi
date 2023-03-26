<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiTrait;

    protected $userRepo ;

    public function __construct(UserRepositoryInterface $userRepoInterface)
    {
        $this->userRepo = $userRepoInterface ;
    }

    public function index()
    {
        $user = User::find(auth()->id()) ;
        $group_id = $user->group_id ;

        if ($group_id == null) return $this->responseJson($user);

        $users = $this->userRepo->getUsersByGroup($group_id);

        if ($users) return $this->responseJson(UserResource::collection($users));

        return $this->responseJsonFailed("no users here");
    }

    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
