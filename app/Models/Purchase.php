<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price',
        'total_price',
        'document_path',
    ];

    // علاقات اختيارية حسب استخدامك
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

