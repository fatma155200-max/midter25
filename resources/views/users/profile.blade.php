@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>الملف الشخصي</h3>
        </div>
        <div class="card-body">
            <p><strong>الاسم:</strong> {{ $user->name }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $user->email }}</p>
            <p><strong>الرصيد الحالي:</strong> {{ $user->credit }} ريال</p>
            
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

            <!-- عرض عمليات الشراء السابقة للمستخدم -->
            <div class="purchases mt-4">
                <h4>عمليات الشراء الخاصة بك:</h4>
                @if($user->purchases->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر الإجمالي</th>
                                <th>تاريخ الشراء</th>
                                <th>المستندات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->product->name }}</td>
                                    <td>{{ $purchase->quantity }}</td>
                                    <td>{{ $purchase->total_price }} ريال</td>
                                    <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($purchase->document_path)
                                            <a href="{{ Storage::url($purchase->document_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                عرض المستند
                                            </a>
                                        @else
                                            <span class="text-muted">لا يوجد مستند</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>لم تقم بأي عمليات شراء بعد.</p>
                @endif
            </div>
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


        </div>
    </div>
</div>
@endsection




