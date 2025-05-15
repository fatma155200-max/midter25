@extends('layouts.master')
@section('title', 'Register')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Register</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('do_register') }}">
                @csrf
                
                {{-- إضافة اختيار نوع المستخدم --}}
                <div class="mb-3">
                    <label class="form-label">Account Type</label>
                    <select name="account_type" class="form-select" required>
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                {{-- سيظهر حقل الرصيد فقط للعملاء --}}
                <div class="mb-3" id="creditField">
                    <label class="form-label">Initial Credit</label>
                    <input type="number" name="credit" class="form-control" value="{{ old('credit', 0) }}" min="0">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
</div>

{{-- إضافة سكربت للتحكم في ظهور حقل الرصيد --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const accountType = document.querySelector('select[name="account_type"]');
    const creditField = document.getElementById('creditField');

    function toggleCreditField() {
        if(accountType.value === 'customer') {
            creditField.style.display = 'block';
        } else {
            creditField.style.display = 'none';
        }
    }

    accountType.addEventListener('change', toggleCreditField);
    toggleCreditField(); // تشغيل عند تحميل الصفحة
});
</script>
@endsection