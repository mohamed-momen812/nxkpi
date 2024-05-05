<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiTrait;
    private $categoryRepo;
    public function __construct(CategoryRepositoryInterface $categoryRepoInterface)
    {
        $this->categoryRepo = $categoryRepoInterface;
    }

    public function index()
    {
//        $categories = $this->categoryRepo->orderedAll();
        $user_id = tenant()->user->id;
        $categories = $this->categoryRepo->getCategoriesByUserId($user_id);

        if (request()->has('name')) {
            $name = strtolower(request()->input('name'));

            $categories = $categories->filter(function ($category) use ($name) {

                return strpos(strtolower($category->name), $name) !== false;
            });
        }

        if (request()->has('kpi_name')) {
            $kpi_name = strtolower(request()->input('kpi_name'));

            $categories = $categories->filter(function ($category) use ($kpi_name) {

                return $category->kpis->filter(function ($kpi) use ($kpi_name) {
                    return strpos(strtolower($kpi->name), $kpi_name) !== false;
                })->isNotEmpty();
            });
        }

        return $this->responseJson($this->dataPaginate(CategoryResource::collection($categories)));

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
    public function store(CategoryRequest $request)
    {
        $input = $request->validated();
        $input['user_id'] = tenant()->user->id;
        $category = $this->categoryRepo->create($input);

        if ($category) {
            return $this->responseJson(CategoryResource::make($category));
        }

        return $this->responseJsonFailed();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->categoryRepo->find($id);

        if ($category) {
            return $this->responseJson(CategoryResource::make($category));
        }

        return $this->responseJsonFailed('Category not found');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd('edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request,$id)
    {
        $input = $request->validated();
        $input['user_id'] = auth()->id();
        $category = $this->categoryRepo->update($input , $id);

        if ($category){
            return $this->responseJson(CategoryResource::make($category),'category updated successfully');
        }

        return $this->responseJsonFailed();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepo->destroy($id);

        if ($category){
            return $this->responseJson('category deleted successfully');
        }

        return $this->responseJsonFailed('category not found');
    }
}
