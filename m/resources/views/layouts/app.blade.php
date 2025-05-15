@extends('layouts.master')
@section('content')
<div>
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
<div class="card">
        <div class="card-header">
            <h3>الملف الشخصي</h3>
        </div>
        <div class="card-body">

            @if(auth()->user()->hasRole('employee') && $user->hasRole('customer'))
                <form action="{{ route('users.add-credit', $user->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>إضافة رصيد</label>
                        <input type="number" name="amount" class="form-control" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </form>
            @endif
        </div>
    </div>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
       
            <li class="nav-item">
                <a class="nav-link" href="{{ route('products.list') }}">المنتجات</a>
            </li>
            @auth
                @if(auth()->user()->hasRole('customer'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('purchases.list') }}">مشترياتي</a>
                    </li>
                @endif
                @if(auth()->user()->hasRole('employee'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.my-customers') }}">عملائي</a>
                    </li>
                @endif
            @endauth
            
        </ul>
    </div>
</nav>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</div>
@endsection