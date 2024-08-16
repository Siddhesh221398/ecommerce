@extends('admin.layouts.index')
@section('content')
    <section class="content mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Category</h3>
                        </div>
                        <form method="POST" action="{{route('category.update',$category->id)}}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @if (count($errors) > 0)
                                    <div class = "alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                    <div class="form-group">
                                        <label for="name">Category</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="0">Select Category</option>
                                            @foreach($categories as $cat)
                                                <option value="{{$cat->id}}" @if($cat->id == $category->parent_id) selected @endif>{{$cat->name}}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$category->name}}" placeholder="Enter name">
                                    <span class="invalid-feedback">

                                    </span>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
