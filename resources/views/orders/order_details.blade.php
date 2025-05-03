@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">تأكيد الطلب</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h5>تفاصيل المنتج</h5>
                    <div class="mb-3">
                        <strong>اسم المنتج:</strong> {{ $product->name }}
                    </div>
                    <div class="mb-3">
                        <strong>السعر:</strong> {{ $product->price }}
                    </div>
                    <div class="mb-3">
                        <strong>المخزون المتاح:</strong> {{ $product->stock }}
                    </div>

                    <form method="POST" action="{{ route('products.confirm-order', $product) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="quantity">الكمية المطلوبة:</label>
                            <input type="number" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" 
                                   name="quantity" 
                                   min="1" 
                                   max="{{ $product->stock }}" 
                                   value="1">
                            @error('quantity')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="document">المستندات المرفقة (اختياري):</label>
                            <input type="file" 
                                   class="form-control @error('document') is-invalid @enderror" 
                                   id="document" 
                                   name="document">
                            @error('document')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <strong>رصيدك الحالي:</strong> {{ auth()->user()->credit }}
                        </div>
                        <button type="submit" class="btn btn-primary">تأكيد الطلب</button>
                        <a href="{{ route('products.list') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection