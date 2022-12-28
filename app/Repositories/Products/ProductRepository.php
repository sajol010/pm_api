<?php

namespace App\Repositories\Products;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\RepositoryPattern;
use Illuminate\Support\Facades\Validator;

class ProductRepository extends RepositoryPattern implements ProductRepositoryInterface
{
    public function all($params=[])
    {
        $products = Product::get();
        $conditions = [];
        return ProductResource::collection($products)->resolve();
    }

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
            $this->saveRelationals($product);
            return ['success'=>true, 'data'=>$product];
        }
        return ['success'=>false, 'errors'=>[], 'status'=>500];
    }

    private function saveRelationals($product){
        if (!empty(request()->category_ids))
            foreach (request()->category_ids as $category_id)
                $product->categories()->attach($product, ['category_id'=>$category_id]);

        if (!empty(request()->images))
            foreach (request()->images as $image)
                $product->images()->save((new ProductImage(['image'=>$image])));

    }

    public function update($id): array
    {
        $validator = Validator::make(request()->all(), [
            'name'=>'required',
            'price'=>'required'
        ]);

        if ($validator->fails())
            return ['success'=>false, 'errors'=>$validator->errors(), 'status'=>422];
        $product = Product::findOrFail($id);
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


    public function findById($id){
        return ProductResource::make(Product::find($id))->resolve();
    }

    public function delete($id){
        $product = Product::findOrFail($id);
        if ($product->delete())
            return true;

        return false;
    }
}
