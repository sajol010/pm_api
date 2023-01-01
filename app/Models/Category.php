<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public function products(){
        return $this->belongsToMany(CategoryProduct::class);
    }

    public function optionFormat(): array
    {
        return [
            'label'=>$this->name,
            'value'=>$this->id
        ];
    }
}
