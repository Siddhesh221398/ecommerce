<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Document;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::with(['category','brand'])->get();
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('product.edit',$row->id).'" class="btn btn-primary"><i class="fa fa-pen"></i></a>';
                    $btn .=' <button type="button" class="ml-2 btn btn-danger btn-delete" data-id="'.$row->id.'"><i class="fa fa-trash"></i>
                                                   </button>';
                    return $btn;
                })
                ->addColumn('status', function ($row) {

                    return view('admin.layouts.statusButton',['product'=>$row])->render();
                })
                ->addColumn('brand', function ($row) {
                     return $row->brand->name;
                })
                ->addColumn('category', function ($row) {
                     return $row->category->name;
                })

                ->setRowClass(function () {
                    return 'row-move';
                })
                ->setRowId(function ($row) {
                    return 'row-' . $row->id;
                })
                ->rawColumns(['action','category','brand','status'])
                ->make(true);
        }
        return view('admin.product.index');
    }

    public function create()
    {
        $brands= Brand::get();
        $categories= Category::where('parent_id',0)->with('subCategories')->get();

        return view('admin.product.create',compact('brands','categories'));

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'category_id'=>'required|exists:categories,id',
            'brand_id'=>'required|exists:brands,id',
            'price'=>'required',
            'description'=>'required',
            'images' => 'required|array',
            'images.*' => 'file|mimes:png,jpg,jpeg,gif|max:10240'
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->is_active = 1;
        $product->save();

        if($request->has('images')){
            $documents = Document::manageUploadDocument($request->images,$product->id, Product::class);
        }

        if(!$product){
            return response()->json(['status'=>'error','message'=>'Something went wrong']);
        }

        return response()->json(['status'=>'success','message'=>'Product Saved Successfully']);

    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        $brands= Brand::get();
        $categories= Category::where('parent_id',0)->with('subCategories')->get();

        return view('admin.product.edit',compact('brands','categories','product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->validate($request,[
            'name'=>'required',
            'category_id'=>'required|exists:categories,id',
            'brand_id'=>'required|exists:brands,id',
            'price'=>'required',
            'description'=>'required',
            'images' => 'array',
            'images.*' => 'file|mimes:png,jpg,jpeg,gif|max:10240'
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->is_active = 1;
        $product->save();

        if($request->has('images')){
            $documents = Document::manageUploadDocument($request->images,$product->id, Product::class);
        }

        if(!$product){
            return response()->json(['status'=>'error','message'=>'Something went wrong']);
        }

        return response()->json(['status'=>'success','message'=>'Product Updated Successfully']);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        if(!$product){
            return response()->json(['status'=>'error','message'=>'Something went wrong']);
        }
        return response()->json(['status'=>'success','message'=>'Product Deleted Successfully']);
    }
    public function documentDestroy(Request $request,Document $document)
    {
        unlink($document->path);
        $document->delete();
        if(!$document){
            return response()->json(['status'=>'error','message'=>'Something went wrong']);
        }
        return response()->json(['status'=>'success','message'=>'Document Deleted Successfully']);
    }
    public function status(Request $request,Product $product)
    {
        $product->is_active = $request->is_active;
        $product->save();
        if(!$product){
            return response()->json(['status'=>'error','message'=>'Something went wrong']);
        }
        return response()->json(['status'=>'success','message'=>'Product Status is changed Successfully']);
    }
}
