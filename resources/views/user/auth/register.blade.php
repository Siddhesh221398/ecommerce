@extends('user.layouts.index')
@section('content')
    <div class="container-fluid fruite py-5 mt-5">
        <div class="container py-5">
            <h1 class="mb-4">Sign <b>Up</b></h1>
            <div class="row g-4">
                <form action="{{route('user.signUp')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group w-75 mx-auto d-flex">
                                <input type="text" name="name" class="form-control p-3" placeholder="Name" aria-describedby="search-icon-1">
                                <span id="user-icon-1" class="input-group-text p-3"><i class="fa fa-user"></i></span>
                            </div>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 mt-4">
                            <div class="input-group w-75 mx-auto d-flex">
                                <input type="email" name="email" class="form-control p-3" placeholder="Email" aria-describedby="search-icon-1">
                                <span id="envelope-icon-1" class="input-group-text p-3"><i class="fa fa-envelope"></i></span>
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 mt-4">
                            <div class="input-group w-75 mx-auto d-flex">
                                <input type="password" name="password" class="form-control p-3" placeholder="Password" aria-describedby="search-icon-1">
                                <span id="eye-icon-1" class="input-group-text p-3"><i class="fa fa-eye"></i></span>
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 mt-4">
                            <div class="input-group w-75 mx-auto d-flex">
                                <input type="password" name="password_confirmation" class="form-control p-3" placeholder="Confirm Password" aria-describedby="search-icon-1">
                                <span id="eye-icon-1" class="input-group-text p-3"><i class="fa fa-eye"></i></span>
                            </div>
                        </div>
                        <div class="col-6 mt-4 offset-3">
                            <button class="btn border border-secondary btn-md-square w-100 bg-white me-4" type="submit">Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

@endpush
