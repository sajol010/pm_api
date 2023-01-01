<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'images'=>$this->serializeImage($this->images),
            'categories'=>$this->serializeCategory($this->categories),
            'category_options'=>$this->serializeCategory($this->category_products),
        ];
    }

    /**
     * @param $images
     * @return array
     */
    private function serializeImage($images){
        if (empty($images))
            return [];
        $img = [];
        foreach ($images as $image){
            $img[] = [
                'id'=>$image->id,
                'image'=>url('storage/' . $image->image),
                'product_id'=>$image->product_id,
            ];
        }
        return $img;
    }

    /**
     * @param $categories
     * @return array
     */
    private function serializeCategory($categories){
        if (empty($categories))
            return [];
        $category = [];

        foreach ($categories as $cat){
            $category[] = $cat->id;
        }
        return $category;
    }
}
