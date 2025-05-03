@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>{{ $product->id ? 'تعديل منتج' : 'إضافة منتج جديد' }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ $product->id ? route('products.save', $product->id) : route('products.save') }}" 
                  method="POST">
                @csrf
                <div class="form-group">
                    <label>الكود</label>
                    <input type="text" name="code" class="form-control" 
                           value="{{ old('code', $product->code) }}" required>
                </div>
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" class="form-control" 
                           value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="form-group">
                    <label>الموديل</label>
                    <input type="text" name="model" class="form-control" 
                           value="{{ old('model', $product->model) }}" required>
                </div>
                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label>السعر</label>
                    <input type="number" name="price" class="form-control" 
                           value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="form-group">
                    <label>الكمية المتوفرة</label>
                    <input type="number" name="stock" class="form-control" 
                           value="{{ old('stock', $product->stock) }}" required>
                </div>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </form>
        </div>
    </div>
</div>
@endsection