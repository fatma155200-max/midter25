@extends('layouts.app')

@section('content')
    <h1>Users List</h1>
    
    <!-- إضافة مستخدم جديد - يظهر فقط إذا كان المستخدم هو Admin -->
    @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
    @endif
    
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <!-- تعديل المستخدم - يظهر فقط إذا كان المستخدم هو Admin -->
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
