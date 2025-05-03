@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">مشترياتي</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table">
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
                            @foreach($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->product->name }}</td>
                                    <td>{{ $purchase->quantity }}</td>
                                    <td>{{ $purchase->total_price }}</td>
                                    <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($purchase->document_path)
                                            <a href="{{ Storage::url($purchase->document_path) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-info">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection