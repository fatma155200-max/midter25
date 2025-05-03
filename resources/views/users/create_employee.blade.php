@extends('layouts.master')
@section('title', 'Create Employee')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Create New Employee</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('store_employee') }}">
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
                <a href="{{ route('create_employee') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection  