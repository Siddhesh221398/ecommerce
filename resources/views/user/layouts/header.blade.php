<div class="container-fluid fixed-top">
    <div class="container topbar bg-primary d-none d-lg-block">
        <div class="d-flex justify-content-between">
            <div class="top-info ps-2">
                <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">123 Street, New York</a></small>
                <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">Email@Example.com</a></small>
            </div>
            <div class="top-link pe-2">
                <a href="#" class="text-white"><small class="text-white mx-2">Privacy Policy</small>/</a>
                <a href="#" class="text-white"><small class="text-white mx-2">Terms of Use</small>/</a>
                <a href="#" class="text-white"><small class="text-white ms-2">Sales and Refunds</small></a>
            </div>
        </div>
    </div>
    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="{{route('homePage')}}" class="navbar-brand"><h1 class="text-primary display-6">Fruitables</h1></a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="{{route('homePage')}}" class="nav-item nav-link active">Home</a>
                    <a href="{{route('products')}}" class="nav-item nav-link">Products</a>
                    <a href="{{route('categories')}}" class="nav-item nav-link">Categories</a>
                    <a href="{{route('brands')}}" class="nav-item nav-link">Brands</a>

                    <a href="contact.html" class="nav-item nav-link">Contact</a>
                </div>
                <div class="d-flex me-0 w-25">
                    @auth
                   <a href="{{route('carts')}}" class="position-relative me-4 my-auto">
                        <i class="fa fa-shopping-bag fa-2x"></i>
                        <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">{{auth()->user()->carts()->count('id')}}</span>
                    </a>
                    <a href="#" class="my-auto mr-2">
                        <i class="fas fa-user fa-2x"></i>
                    </a>
                        <form action="{{route('logout')}}" method="POST" class="m-3">
                            @csrf
                        <button class="btn border border-secondary btn-md-square w-100 bg-white me-4" type="submit">Logout</button>
                        </form>
                    @else
                    <a class="btn border border-secondary btn-md-square w-100 bg-white me-4" href="{{route('user.login')}}">Sign In</a>
                    <a class="btn border border-secondary btn-md-square w-100 bg-white me-4" href="{{route('user.register')}}">Sign Up</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</div>
