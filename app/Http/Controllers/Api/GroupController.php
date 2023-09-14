<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Interfaces\GroupRepositoryInterface;
use App\Models\Group;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\MockObject\Api;

class GroupController extends Controller
{
    use ApiTrait;
    private $groupRepo;

    public function __construct(GroupRepositoryInterface $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group = $this->groupRepo->getGroupByUser( auth()->user() );

        return $this->responseJson( $group ? new GroupResource($group) : null );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        $group = $this->groupRepo->create($request->validated());
        return $this->responseJson( new GroupResource($group) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = $this->groupRepo->find($id);
        return $group ? $this->responseJson( new GroupResource($group) ) : $this->responseJsonFailed( $message = "doesn't exist" , $status=404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(GroupRequest $request,  $id)
    {
        $group = $this->groupRepo->find($id);
        if(!$group)
            return $this->responseJsonFailed($message="doesn't exist" , $status = 404);
        if (! Gate::allows('update-group', $group)) {
            abort(403, 'not authorized');
        }
        $group = $this->groupRepo->update( $request->except('_method') , $id);
        return $this->responseJson( new GroupResource($group) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = $this->groupRepo->destroy($id);
        return $this->responseJson(  );
    }
}
