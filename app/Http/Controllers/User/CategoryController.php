<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories = Category::where('parent_id','<>',0)->with(['parentCategory','products'])->latest()->get();
        $parentCategories = Category::where('parent_id',0)->with(['subCategories'])->latest()->get();
        return view('user.categories',compact('categories','parentCategories'));
    }
}
