<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct()
    {
        // التحقق من تسجيل الدخول لجميع الدوال ما عدا list
        $this->middleware('auth')->except(['list']);
    }

    public function list(Request $request) {
        $query = Product::select("products.*");

        if($request->keywords) {
            $query->where("name", "like", "%$request->keywords%");
        }

        $products = $query->get();
        return view('products.list', compact('products'));
    }

    public function edit(Request $request, Product $product = null)
    {
        // التحقق من الصلاحية
        if(!auth()->check() || !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
    
        $product = $product ?? new Product();
        return view('products.edit', compact('product'));
    }
    
    public function save(Request $request, Product $product = null)
    {
        // التحقق من الصلاحية
        if(!auth()->check() || !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
    
        $validated = $this->validate($request, [
            'code' => ['required', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:128'],
            'model' => ['required', 'string', 'max:256'],
            'description' => ['required', 'string', 'max:1024'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer', 'min:0']
        ]);
    
        $product = $product ?? new Product();
        $product->fill($validated);
        $product->save();
    
        return redirect()->route('products.list')->with('success', 'تم حفظ المنتج بنجاح');
    }
    
    public function delete(Request $request, Product $product)
    {
        // التحقق من الصلاحية
        if(!auth()->check() || !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
    
        $product->delete();
        return redirect()->route('products.list')->with('success', 'تم حذف المنتج بنجاح');
    }
}