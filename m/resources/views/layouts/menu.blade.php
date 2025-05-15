<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./even">Even Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./prime">Prime Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./multable">Multiplication Table</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('products.list')}}">Products</a>
            </li>
        </ul>
        <ul class="navbar-nav">
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{route('profile')}}">{{auth()->user()->name}}</a>
                </li>

                {{-- إضافة قسم خاص بالمدير --}}
                @if(auth()->user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users') }}">Users List</a>
                    </li>
                    
                @endif
                @if(auth()->user()->hasRole('admin'))
   
    <li class="nav-item">
        <a class="nav-link" href="{{ route('create_employee') }}">Add Employee</a>
    </li>
@endif


                <li class="nav-item">
                    <a class="nav-link" href="{{route('do_logout')}}">Logout</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('login')}}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('register')}}">Register</a>
                </li>
            @endauth
        </ul>
    </div>
</nav>