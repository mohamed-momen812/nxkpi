<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Repositories\PermissionRepository;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use ApiTrait;
    private $permissionRepo;

    public function __construct(PermissionRepository $permissionRepo)
    {
        $this->permissionRepo = $permissionRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = $this->permissionRepo->all();
        return $this->responseJson( PermissionResource::collection($permissions) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        $permission = $this->permissionRepo->create($request->validated());
        if ($request->role_ids != null){
            $permission->syncRoles($request->role_ids);
        }
        return $this->responseJson( new PermissionResource($permission) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return $this->responseJson( new PermissionResource($permission) );
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
        $permission = $this->permissionRepo->update($request->except('_method','role_ids') , $id);
        if ($request->role_ids != null){
            $permission->syncRoles($request->role_ids);
        }
        return $this->responseJson( new PermissionResource($permission) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = $this->permissionRepo->destroy($id);
        return $this->responseJson();
    }
}
