<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::with('parentCategory')->get();
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('category.edit',$row->id).'" class="btn btn-primary"><i class="fa fa-pen"></i></a>';
                    $btn .=' <button type="button" class="ml-2 btn btn-danger btn-delete" data-id="'.$row->id.'"><i class="fa fa-trash"></i>
                                                   </button>';
                    return $btn;
                })
                ->addColumn('parent', function ($row) {
                    return $row->parentCategory ? $row->parentCategory->name : '-';
                })

                ->setRowClass(function () {
                    return 'row-move';
                })
                ->setRowId(function ($row) {
                    return 'row-' . $row->id;
                })
                ->rawColumns(['action','parent'])
                ->make(true);
        }
        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('parent_id',0)->latest()->get();
        return view('admin.category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'parent_id'=>'sometimes',
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->save();

        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::where('parent_id',0)->latest()->get();
        return view('admin.category.edit',compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request,[
            'name'=>'required',
        ]);
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->save();

        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if($category->subCategories()->count() > 0){
            $category->subCategories()->delete();
        }
        $category->delete();
        if(!$category){
            return response()->json(['status'=>'error','message'=>'Something went wrong']);
        }
        return response()->json(['status'=>'success','message'=>'Category Deleted Successfully']);

    }
}
