@extends('layouts.master')
@section('title', 'Users List')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Users Management</h3>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('register') }}" class="btn btn-primary">Add New User</a>
            @endif
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Credit</th>
                        @if(auth()->user()->hasRole('admin'))
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-info">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $user->credit ?? 0 }}</td>
                        @if(auth()->user()->hasRole('admin'))
                            <td>
                                <a href="{{ route('users_edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                @if($user->id !== auth()->id())
                                    <a href="{{ route('users_delete', $user->id) }}" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                        Delete
                                    </a>
                                @endif
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($users->hasPages())
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection