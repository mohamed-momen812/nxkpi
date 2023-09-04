<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Traits\ApiTrait;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

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
        $users = $this->userRepo->getUsersByParent( auth()->user()->id );

        if ($users) return $this->responseJson(UserResource::collection($users));

        return $this->responseJsonFailed("no users here");
    }

    public function store(UserRequest $request)
    {
        $data = $request->except('password');
        $data['password'] = Hash::make($request->password);
//        $data['parent_user'] = auth()->user()->id ; tenant()->user->id
        $data['parent_user'] = tenant()->user->id;
        $user = $this->userRepo->create($data);
        $user->assignRole( $request->type );
        if($request->permission_ids){
            foreach ($request->permission_ids as $permission_id){
//                dd($permission_id);
                $permission = Permission::findOrFail($permission_id);
                $user->givePermissionTo($permission->name);
            }
        }
        return ($user != null ) ?  $this->responseJson(new UserResource($user)) : $this->responseJsonFailed() ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepo->find($id);
        return $user ? $this->responseJson(new UserResource($user)) : $this->responseJsonFailed( "user not found") ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->userRepo->find($id);
        if( !$user->parent_user == auth()->user()->id ){
            $this->responseJsonFailed("you havn't the permission to do that");
        }
        $data = $request->except('permission_ids' , '_method');
        $request->password ?? $data['password'] = Hash::make($request->password) ;

        //remove old role
        $user->removeRole($user->type);
        //remove added permission
        if($request->permission_ids){
            foreach ( $user->addedPermissions() as $permission ){
                $user->revokePermissionTo($permission->name);
            }
        }
        $user = $this->userRepo->update($data , $id);
        $user->assignRole( $request->type );
        if($request->permission_ids){
            foreach ($request->permission_ids as $permission_id){
                $permission = Permission::findOrFail($permission_id);
                $user->givePermissionTo($permission->name);
            }
        }

        return ($user != null ) ?  $this->responseJson(new UserResource($user)) : $this->responseJsonFailed() ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userRepo->destroy($id);
        return $this->responseJson();
    }
}
