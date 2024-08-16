@extends('user.layouts.index')
@section('content')
    <div class="container-fluid page-header py-5"></div>
    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row  mb-5">
                <div class="col-lg-12 col-xl-9">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded">
                                <a href="#">
                                    <img src="{{asset($product->document->path)}}" class="img-fluid rounded" alt="Image">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="fw-bold mb-3">{{$product->name}}</h4>
                            <p class="mb-3">Category: {{$product->category->name}}</p>
                            <h5 class="fw-bold mb-3">{{$product->price}} $</h5>

                            <p class="mb-4">{{$product->description}}</p>

                            @auth
                                @if($cart)
                                    <div class="mb-5">

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
                                    <button data-id="{{$product->id}}"
                                            class="btn border border-danger remove-cart rounded-pill px-4 py-2 mb-4 text-danger">
                                        <i class="fa fa-shopping-bag me-2 text-danger"></i> Remove From cart
                                    </button>
                                @else
                                    <button data-id="{{$product->id}}"
                                            class="btn border border-secondary add-cart rounded-pill px-4 py-2 mb-4 text-primary">
                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                    </button>
                                @endif
                            @else
                                <a href="{{route('user.login')}}" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function (){
            $(document).on('click','.add-cart',function (){
                $.ajax({
                    type: "POST",
                    url: "{{route('addToCart')}}",
                    data: {
                        _token:"{{csrf_token()}}",
                        product_id: $(this).data('id')
                    },
                    dataType:'json',

                    success: function (data) {
                        console.log(data.errors)
                        if (data.status === 'success') {
                            // toastr.success(data.message)
                            location.reload()

                        } else if (data.status === 'error') {
                            // toastr.success(data.message)

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
            })
            $(document).on('click','.remove-cart',function (){
                $.ajax({
                    type: "POST",
                    url: "{{route('removeCart')}}",
                    data: {
                        _token:"{{csrf_token()}}",
                        product_id: $(this).data('id')
                    },
                    dataType:'json',

                    success: function (data) {
                        console.log(data.errors)
                        if (data.status === 'success') {
                            // toastr.success(data.message)
                            location.reload()

                        } else if (data.status === 'error') {
                            // toastr.success(data.message)

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
