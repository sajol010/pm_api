<?php

namespace App\Http\Controllers;

use App\Repositories\Categories\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $forOption = (empty($request->for_option) || $request->for_option == 'false')?false:true;
        $data = $this->categoryRepository->all(['for_option'=>$forOption]);
        return $this->success($data);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $response = $this->categoryRepository->save();
        if (!empty($response['success']))
            return $this->success($response['data'], 'Data has been saved.', 201);

        return $this->fail('Something wrong!', $response['errors'], $response['status']??500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $response = $this->categoryRepository->findById($id);
        if (!empty($response))
            return $this->success($response);

        return $this->fail('Category not found', '', 404);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $response = $this->categoryRepository->update($id);

        if (!empty($response['success']))
            return $this->success($response['data'], 'Data has been updated.', 201);

        return $this->fail('Something wrong!', $response['errors'], $response['status']??500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $response = $this->categoryRepository->delete($id);
        if ($response){
            return $this->success([], 'Category has been deleted.', 200);
        }
        return $this->fail('Something wrong!', $response['errors'], $response['status']??500);
    }
}
