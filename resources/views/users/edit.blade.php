@extends('layouts.master')
@section('title', 'Edit User')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Edit User</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('users_save', $user->id) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" readonly>
                </div>

                @if(auth()->user()->hasRole('admin'))
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="roles[]" class="form-select" required>
                            <option value="customer" {{ $user->hasRole('customer') ? 'selected' : '' }}>Customer</option>
                            <option value="employee" {{ $user->hasRole('employee') ? 'selected' : '' }}>Employee</option>
                            <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="mb-3" id="creditField">
                        <label class="form-label">Credit</label>
                        <input type="number" name="credit" class="form-control" 
                               value="{{ old('credit', $user->credit) }}" min="0">
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('users') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="roles[]"]');
    const creditField = document.getElementById('creditField');

    function toggleCreditField() {
        if(roleSelect && roleSelect.value === 'customer') {
            creditField.style.display = 'block';
        } else {
            creditField.style.display = 'none';
        }
    }

    if(roleSelect) {
        roleSelect.addEventListener('change', toggleCreditField);
        toggleCreditField();
    }
});
</script>
@endsection