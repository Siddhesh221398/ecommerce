@extends('admin.layouts.index')
@section('content')
    <section class="content mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Product</h3>
                        </div>
                        <form method="POST" class="productsFrm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Enter name" value="{{$product->name}}">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control"
                                              name="description" id="description">{{$product->description}}</textarea>

                                </div>
                                <div class="form-group">
                                    <label for="images">Photos</label>
                                    <input type="file" class="form-control" name="images[]" id="images" multiple
                                           accept="image/*">

                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" class="form-control" id="price" name="price"
                                           placeholder="Enter price" value="{{$product->price}}">
                                </div>
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" disabled>{{$category->name}}</option>
                                            @foreach($category->subCategories as $subCategory)

                                                <option value="{{$subCategory->id}}"
                                                        @if($product->category_id == $subCategory->id ) selected @endif>
                                                    &nbsp;&nbsp;&nbsp;-- {{$subCategory->name}}</option>

                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="brand_id">Brand</label>
                                    <select name="brand_id" id="brand_id" class="form-control">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand->id}}"
                                                    @if($product->brand_id == $brand->id ) selected @endif>{{$brand->name}}</option>

                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        @foreach($product->documents as $document)
                                           <div class="col-3 d-flex" id="document-{{$document->id}}">
                                               <img src="{{asset($document->path)}}" alt="" height="250" width="250">
                                               <button type="button" class="btn btn-danger btn-delete-image" data-id="{{$document->id}}"><i class="fa fa-trash"></i></button>
                                           </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $(".productsFrm").validate({
                rules:
                    {
                        name:
                            {
                                required: true
                            },
                        brand_id:
                            {
                                required: true
                            },
                        category_id:
                            {
                                required: true
                            },
                        description:
                            {
                                required: true
                            },

                        price:
                            {
                                required: true
                            },


                    },
                messages:
                    {
                        name:
                            {
                                required: "Name is required"
                            },

                        brand_id:
                            {
                                required: 'Select Brand'
                            },
                        category_id:
                            {
                                required: 'Select Category'
                            },
                        description:
                            {
                                required: 'Description is required'
                            },

                        price:
                            {
                                required: 'Price is required'
                            },


                    },
                highlight: function (element) {
                    $(element).closest('.form-group').addClass('has-error');
                    $('.help-block').css('color', 'red');
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            $(".submit").click(function (event) {
                // alert('he');
                event.preventDefault();
                if ($(".productsFrm").valid()) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('product.update',$product->id)}}",
                        data: new FormData($('.productsFrm')[0]),
                        processData: false,
                        contentType: false,
                        cache: false,

                        success: function (data) {
                            if (data.status === 'success') {
                                toastr.success(data.message)
                                window.location = "{{route('product.index')}}"

                            } else if (data.status === 'error') {
                                toastr.success(data.message)

                            }


                        },
                        error: function (errors){

                            $.each(formFields,function (index,field){
                                if(errors.responseJSON.errors[field]){
                                    var html= "<span name="+field+" class='help-block'>"+errors.responseJSON.errors[field]+"</span>"
                                    $('#'+field).closest('.form-group').addClass('has-error').append(html);
                                }

                            })
                            $('.help-block').css('color', 'red');
                            console.log()
                        }
                    });
                } else {
                    event.preventDefault();
                }
            });

            $(document).on('click','.btn-delete-image',function (){
                const id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: "http://127.0.0.1:8000/product/document/"+id,
                    data: {
                        _token: "{{csrf_token()}}",
                        _method: "Delete"

                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.status == 'success') {
                            toastr.success(data.message)
                            $('#document-'+id).remove()

                        }else{
                            toastr.error(data.message)
                        }
                    }
                });
            })


        });
    </script>
@endpush
