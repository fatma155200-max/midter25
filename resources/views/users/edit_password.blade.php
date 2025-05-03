@extends('layouts.master')
@section('title', 'Edit User')
@section('content')
<div class="d-flex justify-content-center">
    <div class="row m-4 col-sm-8">
        <form action="{{route('save_password', $user->id)}}" method="post">
            {{ csrf_field() }}
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">
            <strong>Error!</strong> {{$error}}
            </div>
            @endforeach

            @if(!auth()->user()->hasPermissionTo('admin_users') || auth()->id()==$user->id)
                <div class="row mb-2">
                    <div class="col-12">
                        <label class="form-label">Old Password:</label>
                        <input type="password" class="form-control" placeholder="Old Password" name="old_password" required>
                    </div>
                </div>
            @endcan

            <div class="row mb-2">
                <div class="col-12">
                    <label class="form-label">Password:</label>
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
            </div>
            
            <div class="row mb-2">
                <div class="col-12">
                    <label class="form-label">Password Confirmtion:</label>
                    <input type="password" class="form-control" placeholder="Password Confirmtion" name="password_confirmation" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <div class="card-header">
            <h3>Create New Employee</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Create Employee</button>
                <a href="{{ route('users.list') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection