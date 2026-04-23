<?php
// app/Http/Controllers/ProductPurchaseController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductPurchaseList;
use App\Models\ManageReport;

class ProductPurchaseController extends Controller
{
    // ── Show form + history ───────────────────────────────────────────
    public function index()
{
    $products = Product::with(['category', 'subcategory'])
                    ->where('status', 1)
                    ->get(['id', 'name', 'base_price', 'smart_points', 'hsn_code', 'category_id', 'subcategory_id']);

    // Pre-map for JS — avoids Blade @json + closure bug
    $productsForJs = $products->map(function ($p) {
        return [
            'id'   => $p->id,
            'name' => $p->name,
            'base' => $p->base_price,
            'sp'   => $p->smart_points,
            'hsn'  => $p->hsn_code ?? 'N/A',
            'cat'  => optional($p->category)->name ?? '',
            'sub'  => optional($p->subcategory)->name ?? '',
        ];
    })->values();

    // $purchases = ProductPurchase::with(['items', 'member'])->latest()->get();

    $purchases = ProductPurchase::with(['member', 'items'])
    ->orderBy('id', 'desc')   // ✅ THIS is the root fix
    ->paginate(50);

    return view('admin.productpurchase', compact('products', 'productsForJs', 'purchases'));
}

    // ── Store purchase ────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'purchase_date' => 'required|date_format:Y-m-d\TH:i',
            'product_ids'   => 'required|array|min:1',
            'product_ids.*' => 'required|exists:products,id',
            'dp.*'          => 'required|numeric|min:0',
        ]);

        $lastInvoice = ProductPurchase::where('invoice_no', 'like', 'SBES%')
                        ->orderBy('id', 'desc')->first();
        $sequence    = $lastInvoice
                        ? (intval(substr($lastInvoice->invoice_no, -5)) + 1)
                        : 1;
        $invoiceNo   = 'SBES' . str_pad($sequence, 5, '0', STR_PAD_LEFT);

        $productIds         = $request->product_ids;
        $dps                = $request->dp;
        $grandTotal         = 0;
        $totalSmartPoint    = 0;
        $totalSmartQuantity = 0;
        $listItems          = [];

        foreach ($productIds as $i => $productId) {
            $product = Product::find($productId);
            if (!$product) continue;

            $dp    = floatval($dps[$i] ?? 0);
            $base  = floatval($product->base_price);
            $count = $base > 0 ? $dp / $base : 0;
            $sp    = floatval($product->smart_points);
            $sq    = $sp * 0.001;

            $grandTotal         += $dp;
            $totalSmartPoint    += $dp * $sp;   // ✅ FIXED: dp × sp (matches frontend)
            $totalSmartQuantity += $dp * $sq;   // ✅ FIXED: dp × sq (matches frontend)

            $listItems[] = [
                'purchase_id'        => 0,       // placeholder, updated below
                'member_id'          => $request->member_id,
                'product_id'         => $productId,
                'product_name'       => $product->name,
                'product_hsn'        => $product->hsn_code ?? '',
                'product_baseprice'  => $base,
                'product_dp'         => $dp,
                'product_count'      => $count,
                'product_smartpoints'=> $sp,
                'product_smartqty'   => $sq,
                'product_total'      => $dp,
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }
        
        // ✅ Determine who is adding
        $addedById = null;
        
        if (session('type') == 'Admin') {
            $addedById = session('admin_id');
        } elseif (session('type') == 'Member') {
            $addedById = session('member_id');
        }

        // Save parent purchase
        $purchase = ProductPurchase::create([
            'member_id'           => $request->member_id,
            'invoice_no'          => $invoiceNo,
            'purchase_date'       => $request->purchase_date,
            'total'               => $grandTotal,
            'total_smartpoint'    => $totalSmartPoint,
            'total_smartquantity' => $totalSmartQuantity,
            'status'              => 1,
             'added_by_id'         => $addedById, // ✅ NEW FIELD
        ]);
        
        

        // Inject real purchase_id then insert
        foreach ($listItems as &$item) {
            $item['purchase_id'] = $purchase->id;
        }
        ProductPurchaseList::insert($listItems);
        
        // ✅ NEW LOGIC: Member Report Status Update
        $memberReport = ManageReport::where('memberID', $request->member_id)->first();
        
        if ($memberReport && $memberReport->status == 2) {
            $memberReport->status = 1; // Active / Approved
            $memberReport->save();
        }

        return redirect()->route('productpurchase.index')
            ->with('success', "Purchase saved! Invoice: {$invoiceNo}");
    }

    // ── AJAX: member lookup ───────────────────────────────────────────

    public function memberLookup(Request $request)
    {
        $search = $request->get('member_id');

        $members = ManageReport::where('memberID', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->limit(8)
                    ->get(['memberID', 'name', 'phone']);

        if ($members->count()) {
            return response()->json([
                'found'   => true,
                'results' => $members,
            ]);
        }

        return response()->json(['found' => false, 'results' => []]);
    }

    // ── Bulk Delete ───────────────────────────────────────────────────
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('productpurchase.index')
                ->with('error', 'No purchases selected.');
        }

        // ✅ FIXED: correct model name ProductPurchaseList (not ProductPurchaseItem)
        ProductPurchaseList::whereIn('purchase_id', $ids)->delete();
        ProductPurchase::whereIn('id', $ids)->delete();

        return redirect()->route('productpurchase.index')
            ->with('success', count($ids) . ' purchase(s) deleted successfully.');
    }
}
