<?php

namespace App\Repositories\Categories;

use App\Models\Category;
use App\Repositories\RepositoryPattern;
use Illuminate\Support\Facades\Validator;

class CategoryRepository extends RepositoryPattern implements CategoryRepositoryInterface
{
    public function all($params=[])
    {
        $categories = Category::all();
        if (!empty($params['for_option'])){
            $categories =$categories = $categories->map->optionFormat();
        }
        return $categories;
    }

    public function save(): array
    {
        $validator = Validator::make(request()->all(), [
           'name'=>'required'
        ]);

        if ($validator->fails())
            return ['success'=>false, 'errors'=>$validator->errors(), 'status'=>422];

        $category = new Category();
        $category->name = request()->name;
        $category->parent_id = request()->parent_id??0;

        if ($category->save()){
            return ['success'=>true, 'data'=>$category];
        }
        return ['success'=>false, 'errors'=>[], 'status'=>500];
    }


    public function update($id): array
    {
        $validator = Validator::make(request()->all(), [
            'name'=>'required'
        ]);

        if ($validator->fails())
            return ['success'=>false, 'errors'=>$validator->errors(), 'status'=>422];
        $category = Category::findOrFail($id);
        $category->name = request()->name;
        $category->parent_id = request()->parent_id??0;
        if ($category->save()){
            return ['success'=>true, 'data'=>$category];
        }
        return ['success'=>false, 'errors'=>[], 'status'=>500];
    }


    public function findById($id){
        return Category::find($id);
    }

    public function delete($id){
        $product = Category::findOrFail($id);
        if ($product->delete())
            return true;

        return false;
    }
}
