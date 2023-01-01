<?php

namespace App\Http\Controllers;

use App\Repositories\Products\ProductRepositoryInterface;
use Illuminate\Http\{JsonResponse, Request};

class ProductController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit??10;
        $page = $request->page??1;
        $offset = $limit * ($page-1);
        $data = $this->productRepository->all(['limit'=>$limit, 'offset'=>$offset]);
        $responseData = [
            'list'=>$data,
            'limit'=>$limit,
            'page'=>$page,
            'total'=> 21
        ];
        return $this->success($responseData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $response = $this->productRepository->save();
        if (!empty($response['success']))
            return $this->success($response['data'], 'Data has been saved.', 201);

        return $this->fail('Something wrong!', $response['errors'], $response['status']??500);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $response = $this->productRepository->findById($id);
        if (!empty($response))
            return $this->success($response);

        return $this->fail('Product not found', '', 404);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $response = $this->productRepository->update($id);

        if (!empty($response['success']))
            return $this->success($response['data'], 'Data has been updated.', 201);

        return $this->fail('Something wrong!', $response['errors'], $response['status']??500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $response = $this->productRepository->delete($id);
        if ($response){
            return $this->success([], 'Product has been deleted.', 200);
        }
        return $this->fail('Something wrong!', $response['errors'], $response['status']??500);
    }
}
