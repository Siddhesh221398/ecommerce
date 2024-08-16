@extends('user.layouts.index')
@section('content')
    <div class="container-fluid page-header py-5"></div>
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Products</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Handle</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(auth()->user()->carts as $cart)
                    <tr>
                        <th scope="row">
                            <div class="d-flex align-items-center">
                                <img src="{{asset($cart->product->document->path)}}" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="">
                            </div>
                        </th>
                        <td>
                            <p class="mb-0 mt-4">{{$cart->product->name}}</p>
                        </td>
                        <td>
                            <p class="mb-0 mt-4" id="price-{{$cart->id}}" >{{$cart->price}} $</p>
                        </td>
                        <td>
                            <div class="input-group quantity mt-4" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border" data-qty="{{$cart->qty}}" data-id="{{$cart->product_id}}" data-cartid="{{$cart->id}}">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center border-0 qty-{{$cart->id}}"  value="{{$cart->qty}}">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border" data-qty="{{$cart->qty}}" data-id="{{$cart->product_id}}" data-cartid="{{$cart->id}}">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="mb-0 mt-4" id="total-price-{{$cart->id}}" >{{$cart->price * $cart->qty}} $</p>
                        </td>
                        <td>
                            <button class="btn btn-md rounded-circle bg-light border mt-4 remove-cart" data-id="{{$cart->product_id}}">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                        </td>

                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row g-4 justify-content-end">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                    <div class="bg-light rounded">
                        <div class="p-4">
                            <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                            <div class="d-flex justify-content-between mb-4">
                                <h5 class="mb-0 me-4">Subtotal:</h5>
                                <p class="mb-0 total-price">${{auth()->user()->carts()->sum('total_price')}}</p>
                            </div>

                        </div>
                        <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                            <h5 class="mb-0 ps-4 me-4">Total</h5>
                            <p class="mb-0 pe-4 total-price">${{auth()->user()->carts()->sum('total_price')}}</p>
                        </div>
                        <a class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4" href="{{route('checkout')}}">Proceed Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function (){

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
                let cart_id = $(this).data('cartid');

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
                            let price =  parseInt($('#price-'+cart_id).html())
                            let qty =  parseInt($('.qty-'+cart_id).val())

                            $('#total-price-'+cart_id).html((price*(qty+1)) +' $')
                            console.log(data.total_price)
                            $('.total-price').html('$'+data.total_price)

                            // console.log('#price-'+cart_id)

                        } else if (data.status === 'error') {
                            // toastr.success(data.message)
                            location.reload()
                        }
                    },

                });
            })
            $(document).on('click', '.btn-minus', function () {
                let cart_id = $(this).data('cartid');

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
                            }else{
                                let price =  parseInt($('#price-'+cart_id).html())
                                let qty =  parseInt($('.qty-'+cart_id).val())

                                $('#total-price-'+cart_id).html((price*qty) +' $')
                                $('.total-price').html('$'+data.total_price)
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
