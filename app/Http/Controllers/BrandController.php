<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
class BrandController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Brand::get();
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('brand.edit',$row->id).'" class="btn btn-primary"><i class="fa fa-pen"></i></a>';
                    $btn .=' <button type="button" class="ml-2 btn btn-danger btn-delete" data-id="'.$row->id.'"><i class="fa fa-trash"></i>
                                                   </button>';
                    return $btn;
                })

                ->setRowClass(function () {
                    return 'row-move';
                })
                ->setRowId(function ($row) {
                    return 'row-' . $row->id;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.brand.index');
    }


    public function create()
    {
        return view('admin.brand.create');
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
        ]);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->save();

        return redirect()->route('brand.index');
    }


    public function show(Brand $brand)
    {
        //
    }


    public function edit(Brand $brand)
    {
        return view('admin.brand.edit',compact('brand'));

    }


    public function update(Request $request, Brand $brand)
    {
        $this->validate($request,[
            'name'=>'required',
        ]);

        $brand->name = $request->name;
        $brand->save();

        return redirect()->route('brand.index');
    }


    public function destroy(Brand $brand)
    {
       $brand->delete();
       if(!$brand){
           return response()->json(['status'=>'error','message'=>'Something went wrong']);
       }
        return response()->json(['status'=>'success','message'=>'Brand Deleted Successfully']);

    }
}
