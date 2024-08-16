<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function products(Request $request)
    {

        $categories = Category::where('parent_id','<>',0)->with(['parentCategory','products'])->latest()->get();
        $parentCategories = Category::where('parent_id',0)->with(['subCategories'])->latest()->get();
        $brands = Brand::with(['products'])->latest()->get();
        $carts = Cart::where('user_id',auth()->id())->get();
        $products = Product::when($request->search, function ($q) use ($request){
                $q->where('name','LIKE','%'.$request->search.'%');
        })
        ->when($request->brand && $request->brand != 'all', function ($q) use ($request){
            $q->whereHas('brand', function ($sq) use($request){
                $sq->where('name',$request->brand);
            });
        })
        ->when($request->categories , function ($q) use ($request){
            $q->whereHas('category', function ($sq) use($request){
                $sq->whereIn('name',$request->categories);
            });
        })
        ->latest()->get();
        return view('user.products',compact('parentCategories','brands','products','request','carts'));
    }
    public function singleProduct(Request $request, Product $product)
    {
        $cart = Cart::where(['user_id'=>auth()->id(),'product_id'=>$product->id])->first();

        return view('user.single_product', compact('product','cart'));
    }
}
