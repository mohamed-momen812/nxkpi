<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Repositories\RoleRepository;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ApiTrait;
    private $roleRepo;

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->roleRepo->all();
        return $this->responseJson( RoleResource::collection($roles) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $role = $this->roleRepo->create($request->validated());
        if($request->permission_ids != null){
            $role->syncPermissions($request->permission_ids);
        }
        return $this->responseJson( new RoleResource($role) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return $this->responseJson( new RoleResource($role) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleRepo->update($request->except('permission_ids','_method') , $id);
        if($request->permission_ids != null){

            $role->syncPermissions($request->permission_ids);
        }
        return $this->responseJson( new RoleResource($role) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($role)
    {
        $role = $this->roleRepo->destroy($role);
        return $this->responseJson();
    }
}
