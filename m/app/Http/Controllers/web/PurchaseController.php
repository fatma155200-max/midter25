<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    // عرض تفاصيل الطلب مع المنتج
    public function order(Product $product)
    {
        // التحقق من توفر المنتج في المخزون
        if ($product->stock <= 0) {
            return back()->with('error', 'المنتج غير متوفر حالياً');
        }

        return view('orders.order_details', compact('product'));
    }

    public function purchase(Product $product)
    {
        // تحقق من توفر المنتج في المخزون
        if ($product->stock <= 0) {
            // إذا المنتج غير متوفر، أظهر رسالة خطأ
            return back()->with('error', 'المنتج غير متوفر حالياً');
        }
    
        // عرض تفاصيل الطلب مع المنتج المختار
        return view('orders.order_details', compact('product'));
    }
    
    public function confirmOrder(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
            'document' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $total = $product->price * $validated['quantity'];

        if (auth()->user()->credit < $total) {
            return back()->with('error', 'رصيدك غير كافي لإتمام عملية الشراء');
        }

        try {
            DB::beginTransaction();

            // إنشاء عملية الشراء
            $purchase = Purchase::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price, // ← أضفنا السعر الفردي هنا
                'total_price' => $total,
            ]);
            

            // معالجة الملف المرفق إذا وجد
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $path = $file->store('purchase_documents/' . $purchase->id, 'public');
                $purchase->document_path = $path;
                $purchase->save();
            }

            // تحديث المخزون
            $product->stock -= $validated['quantity'];
            $product->save();

            // خصم الرصيد
            auth()->user()->credit -= $total;
            auth()->user()->save();

            DB::commit();

            return redirect()->route('purchases.list')
                           ->with('success', 'تمت عملية الشراء بنجاح');
                        } catch (\Exception $e) {
                            DB::rollback();
                            return back()->with('error', 'حدث خطأ أثناء عملية الشراء: ' . $e->getMessage());
                        }
                        
    }
    
    // عرض قائمة المشتريات
    public function list()
    {
        $purchases = auth()->user()->purchases()
                          ->with('product')
                          ->latest()
                          ->get();
        return view('purchases.list', compact('purchases'));
    }
}
