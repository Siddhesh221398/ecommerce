<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id','id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function documents()
    {
        return $this->hasMany(Document::class,'model_id','id')->where('model_type',self::class);

    }
    public function document()
    {
        return $this->hasOne(Document::class,'model_id','id')->where('model_type',self::class);

    }
}
