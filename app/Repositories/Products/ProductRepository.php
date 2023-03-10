<?php

namespace App\Repositories\Products;

use App\Http\Resources\ProductResource;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\RepositoryPattern;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductRepository extends RepositoryPattern implements ProductRepositoryInterface
{
    public function all($params=[])
    {
        if (!empty($params))
            $products = Product::with(['images'])->limit($params['limit'])->offset($params['offset'])->get();
        else
            $products = Product::all();


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
        $product->slug = $this->makeUniqueSlug(Str::slug($product->name));
        $product->price = request()->price;
        $product->quantity = request()->quantity;
        $product->description = request()->description??'';


        if ($product->save()){
            $this->saveRelationals($product);
            return ['success'=>true, 'data'=>$product];
        }
        return ['success'=>false, 'errors'=>[], 'status'=>500];
    }

    private function makeUniqueSlug($slug){
        $product = Product::where('slug',$slug)->first();
        if (!empty($product)){
            $slug .= 1;
            return $this->makeUniqueSlug($slug);
        }
        return $slug;
    }

    private function saveRelationals($product){
        if (!empty(request()->category_ids)){
            $existings = CategoryProduct::where(['product_id'=>$product->id])->get();
            foreach ($existings as $prod)
                $prod->delete();
            foreach (explode(',', request()->category_ids) as $category_id)
                $product->categories()->attach($product, ['category_id'=>$category_id]);
        }

        if (!empty(request()->images)){
            $existings = ProductImage::where(['product_id'=>$product->id])->get();
            foreach ($existings as $prod)
                $prod->delete();

            foreach (request()->images as $image){
                $imageName = time() .'.'. $image->getClientOriginalExtension();
                    $image->storeAs('public/images/products/', $imageName);
                    $finalName = '/images/products/' . $imageName;
                    $product->images()->save((new ProductImage(['image'=>$finalName])));

//                }
            }
        }


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
        $product->slug = $this->makeUniqueSlug(Str::slug($product->name));
        $product->price = request()->price;
        $product->quantity = request()->quantity;
        $product->description = request()->description??'';
        if ($product->save()){
            $this->saveRelationals($product);
            return ['success'=>true, 'data'=>$product];
        }
        return ['success'=>false, 'errors'=>[], 'status'=>500];
    }


    public function findById($id){
        $product = Product::where('id',$id)->with(['images', 'categories'])->first();

        return ProductResource::make($product)->resolve();
    }

    public function delete($id){

        $product = Product::findOrFail($id);
        if ($product->delete())
            return true;

        return false;
    }
}
