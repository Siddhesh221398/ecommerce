@extends('user.layouts.index')
@section('content')
    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4">Fresh fruits shop</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <form action="{{route('products')}}" method="GET">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <div class="input-group w-100 mx-auto d-flex">
                                            <input type="search" name="search" class="form-control p-3"
                                                   placeholder="keywords" aria-describedby="search-icon-1"
                                                   value="{{old('search',$request->search)}}">
                                            <span id="search-icon-1" class="input-group-text p-3"><i
                                                    class="fa fa-search"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <h4>Categories</h4>
                                            <ul class="list-unstyled fruite-categorie">
                                                @foreach($parentCategories as $parentCategory)
                                                    <li>
                                                        <div class="d-flex justify-content-between fruite-name">
                                                            <a href="#"><i
                                                                    class="fas fa-apple-alt me-2"></i>{{$parentCategory->name}}
                                                            </a>
                                                            {{--                                                        <span>({{$parentCategory->subCategories()->count()}})</span>--}}
                                                        </div>
                                                        @if($parentCategory->subCategories)
                                                            <ul class="list-unstyled fruite-categorie ml-5 pl-5"
                                                                style="padding-left:20px; ">
                                                                @foreach($parentCategory->subCategories as $subCategory)
                                                                    <li>
                                                                        <div class="mb-2">
                                                                            <input type="checkbox" class="me-2"
                                                                                   id="category-{{$subCategory->id}}"
                                                                                   name="categories[]"
                                                                                   value="{{$subCategory->name}}"
                                                                                   @if($request->categories && in_array($subCategory->name,$request->categories)) checked @endif>
                                                                            <label
                                                                                for="category-{{$subCategory->id}}"> {{$subCategory->name}}
                                                                                &nbsp;
                                                                                ({{$subCategory->products()->count()}}
                                                                                )</label>
                                                                        </div>

                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif

                                                    </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <h4>Brands</h4>
                                            <div class="mb-2">
                                                <input type="radio" class="me-2" id="brand-0" name="brand" value="all"
                                                       @if($request->brand == 'all' || $request->brand == '') checked @endif>
                                                <label for="brand-0"> All</label>
                                            </div>
                                            @foreach($brands as $brand)
                                                <div class="mb-2">
                                                    <input type="radio" class="me-2" id="brand-{{$brand->id}}"
                                                           name="brand" value="{{$brand->name}}"
                                                           @if($request->brand == $brand->name) checked @endif>
                                                    <label for="brand-{{$brand->id}}"> {{$brand->name}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="btn border border-secondary btn-md-square w-100 bg-white me-4"
                                                type="submit">Filter
                                        </button>
                                    </div>
                                    <div class="col-lg-6">
                                        <a class="btn border border-secondary btn-md-square w-100 bg-white me-4"
                                           href="{{route('products')}}">Refresh</a>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="position-relative">
                                            <img src="{{asset('user-assets/img/banner-fruits.jpg')}}"
                                                 class="img-fluid w-100 rounded" alt="">
                                            <div class="position-absolute"
                                                 style="top: 50%; right: 10px; transform: translateY(-50%);">
                                                <h3 class="text-secondary fw-bold">Fresh <br> Fruits <br> Banner</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-9">
                            <div class="row g-4 justify-content-center">
                                @foreach($products as $product)
                                    <div class="col-md-6 col-lg-6 col-xl-4">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="{{asset($product->document->path)}}"
                                                     class="img-fluid w-100 rounded-top "
                                                     style=" height: 200px !important;">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                 style="top: 10px; left: 10px;">{{$product->category->name}}</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <a href="{{route('singleProduct',$product->id)}}">{{$product->name}}</a>
                                                <h5>{{$product->brand->name}}</h5>
                                                @auth
                                                    @if($carts->where('product_id',$product->id)->first())
                                                        @php
                                                            $cart = $carts->where('product_id',$product->id)->first();
                                                        @endphp
                                                        <div class="d-flex justify-content-between flex-lg-wrap mb-5">
                                                            <p class="text-dark fs-5 fw-bold mb-0">${{$product->price}}</p>
                                                            <div class="input-group quantity "
                                                                 style="width: 100px;">
                                                                <div class="input-group-btn">
                                                                    <button
                                                                        class="btn btn-sm btn-minus rounded-circle bg-light border"
                                                                        data-id="{{$product->id}}">
                                                                        <i class="fa fa-minus"></i>
                                                                    </button>
                                                                </div>
                                                                <input type="text"
                                                                       class="form-control form-control-sm text-center border-0"
                                                                       value="{{$cart->qty}}" data-id="{{$cart->id}}">
                                                                <div class="input-group-btn">
                                                                    <button
                                                                        class="btn btn-sm btn-plus rounded-circle bg-light border"
                                                                        data-id="{{$product->id}}">
                                                                        <i class="fa fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <button data-id="{{$product->id}}"
                                                                    class="btn border border-danger remove-cart rounded-pill px-4 py-2 mb-4 text-danger">
                                                                <i class="fa fa-shopping-bag me-2 text-danger"></i>
                                                                Remove From cart
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="d-flex justify-content-between flex-lg-wrap">
                                                            <p class="text-dark fs-5 fw-bold mb-0">${{$product->price}}</p>
                                                        </div>
                                                        <div class="mt-5">
                                                            <button data-id="{{$product->id}}"
                                                                    class="btn border border-secondary add-cart rounded-pill px-4 py-2 mb-4 text-primary">
                                                                <i class="fa fa-shopping-bag me-2 text-primary"></i> Add
                                                                to cart
                                                            </button>
                                                        </div>
                                                    @endif

                                                @else
                                                    <div class="mt-2 mb-0 w-100">
                                                        <a href="{{route('user.login')}}"
                                                           class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary"><i
                                                                class="fa fa-shopping-bag me-2 text-primary"></i> Add to
                                                            cart</a>
                                                    </div>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fruits Shop End-->
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.add-cart', function () {
                $.ajax({
                    type: "POST",
                    url: "{{route('addToCart')}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        product_id: $(this).data('id')
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.errors)
                        if (data.status === 'success') {
                            // toastr.success(data.message)
                            location.reload()
                        } else if (data.status === 'error') {
                            // toastr.success(data.message)
                        }
                    },
                    error: function (errors) {

                        $.each(formFields, function (index, field) {
                            if (errors.responseJSON.errors[field]) {
                                var html = "<span name=" + field + " class='help-block'>" + errors.responseJSON.errors[field] + "</span>"
                                $('#' + field).closest('.form-group').addClass('has-error').append(html);
                            }

                        })
                        $('.help-block').css('color', 'red');
                        console.log()
                    }
                });
            })
            $(document).on('click', '.remove-cart', function () {
                $.ajax({
                    type: "POST",
                    url: "{{route('removeCart')}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        product_id: $(this).data('id')
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.errors)
                        if (data.status === 'success') {
                            // toastr.success(data.message)
                            location.reload()
                        } else if (data.status === 'error') {
                            // toastr.success(data.message)
                        }
                    },
                    error: function (errors) {
                        $.each(formFields, function (index, field) {
                            if (errors.responseJSON.errors[field]) {
                                var html = "<span name=" + field + " class='help-block'>" + errors.responseJSON.errors[field] + "</span>"
                                $('#' + field).closest('.form-group').addClass('has-error').append(html);
                            }
                        })
                        $('.help-block').css('color', 'red');
                        console.log()
                    }
                });
            })
            $(document).on('click', '.btn-plus', function () {
                $.ajax({
                    type: "POST",
                    url: "{{route('increaseQty')}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        product_id: $(this).data('id')
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.errors)
                        if (data.status === 'success') {
                            // toastr.success(data.message)

                        } else if (data.status === 'error') {
                            // toastr.success(data.message)
                            location.reload()
                        }
                    },

                });
            })
            $(document).on('click', '.btn-minus', function () {
                $.ajax({
                    type: "POST",
                    url: "{{route('decreaseQty')}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        product_id: $(this).data('id')
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.errors)
                        if (data.status === 'success') {
                            if(data.cart == 0){
                                location.reload()
                            }
                        } else if (data.status === 'error') {
                            // toastr.success(data.message)
                            location.reload()
                        }
                    },

                });
            })

        })
    </script>
@endpush
