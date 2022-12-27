<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductRepository extends RepositoryPattern
{
    public function save(): array
    {
        $validator = Validator::make(request()->all(), [
           'name'=>'required',
           'price'=>'required'
        ]);

        if ($validator->fails())
            return ['success'=>false, 'errors'=>$validator->errors(), 'status'=>422];
        $product = new Product();
        $product->name = request()->name;
        $product->slug = request()->slug;
        $product->price = request()->price;
        $product->quantity = request()->quantity;
        $product->description = request()->description??'';
        if ($product->save()){
            return ['success'=>true, 'data'=>$product];
        }
        return ['success'=>false, 'errors'=>[], 'status'=>500];
    }
}
