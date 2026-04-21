<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;  

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductPurchaseList;
use App\Models\ManageReport;
use App\Models\Transaction;

class MemberProductPurchaseController extends Controller
{
    // ── Show form + history ───────────────────────────────────────────
    public function purchaseList($pagename)
    {
        $products = Product::with(['category', 'subcategory'])
                        ->where('status', 1)
                        ->get(['id', 'name', 'base_price', 'smart_points', 'hsn_code', 'category_id', 'subcategory_id']);

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

        $query = ProductPurchase::with(['member', 'items']);
        if ($pagename == 'self') {
            $query->where('member_id', session('member_memberID'));
        } elseif ($pagename == 'other') {
            $query->where('member_id', '!=', session('member_memberID'))
                ->where('added_by_id', session('member_id'));
        }
        $purchases = $query->orderBy('id', 'desc')->paginate(50);

        // ✅ Fetch wallet balance using 'memberID' (camelCase — matches ManageReport table)
        $smartWalletBalance = 0;
        $sessionMemberId = session('member_memberID');
        if ($sessionMemberId) {
            $smartWalletBalance = ManageReport::where('memberID', $sessionMemberId)
                                    ->value('smart_wallet_balance') ?? 0;
        }

        return view('member.productpurchase', compact(
            'products',
            'productsForJs',
            'purchases',
            'smartWalletBalance'
        ));
    }

    public function store(Request $request)
{
    $request->validate([
        'purchase_date' => 'required|date_format:Y-m-d\TH:i',
        'product_ids'   => 'required|array|min:1',
        'product_ids.*' => 'required|exists:products,id',
        'dp.*'          => 'required|numeric|min:0',
    ]);

    $memberId = !empty($request->member_id) ? trim($request->member_id) : null;

    $loggedInMemberID = session('member_memberID');
    $isSelfPurchase   = ($memberId === $loggedInMemberID);

    if ($isSelfPurchase && $memberId) {
        $walletBalance = ManageReport::where('memberID', $loggedInMemberID)
                            ->value('smart_wallet_balance') ?? 0;

        $grandTotalCheck = array_sum(array_map('floatval', $request->dp ?? []));

        if ($grandTotalCheck > $walletBalance) {
            return back()
                ->withInput()
                ->withErrors([
                    'wallet' => 'Your Smart Wallet balance is ₹' . number_format($walletBalance, 2) .
                                ', but the bill total is ₹' . number_format($grandTotalCheck, 2) . '.'
                ]);
        }
    }


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
        $totalSmartPoint    += $dp * $sp;
        $totalSmartQuantity += $dp * $sq;

        $listItems[] = [
            'purchase_id'         => 0,
            'member_id'           => $memberId,
            'product_id'          => $productId,
            'product_name'        => $product->name,
            'product_hsn'         => $product->hsn_code ?? '',
            'product_baseprice'   => $base,
            'product_dp'          => $dp,
            'product_count'       => $count,
            'product_smartpoints' => $sp,
            'product_smartqty'    => $sq,
            'product_total'       => $dp,
            'created_at'          => now(),
            'updated_at'          => now(),
        ];
    }

    $addedById = null;
    if (session('type') == 'Admin') {
        $addedById = session('admin_id');
    } elseif (session('type') == 'Member') {
        $addedById = session('member_id');
    }

    $purchase = ProductPurchase::create([
        'member_id'           => $memberId,
        'invoice_no'          => $invoiceNo,
        'purchase_date'       => $request->purchase_date,
        'total'               => $grandTotal,
        'total_smartpoint'    => $totalSmartPoint,
        'total_smartquantity' => $totalSmartQuantity,
        'status'              => 1,
        'added_by_id'         => $addedById,
    ]);

    foreach ($listItems as &$item) {
        $item['purchase_id'] = $purchase->id;
    }
    unset($item);
    ProductPurchaseList::insert($listItems);


    if ($isSelfPurchase && $memberId) {

        $memberReport = ManageReport::where('memberID', $memberId)->first();
        if ($memberReport && $memberReport->status == 2) {
            $memberReport->status = 1;
            $memberReport->save();
        }
    }
        ManageReport::where('member_id', $addedById)
            ->decrement('smart_wallet_balance', $grandTotal);
        Transaction::create([

            'member_id'           => $memberId        ?? 'M000001',
            'added_by_id'         => $addedById                       ?? '',
            'amount'              => $grandTotal            ?? '',
            'action'              => 'Product Purchase',
            'type'                => 'Debit',
            'status'              => 1,
            'created_at'          => Now(),
        ]);

    $pagename = $isSelfPurchase ? 'self' : 'other';

    return redirect()->route('member.productpurchase.purchaseList', $pagename)
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
            return redirect()->route('member.productpurchase.purchaseList', 'other')
                ->with('error', 'No purchases selected.');
        }

        ProductPurchaseList::whereIn('purchase_id', $ids)->delete();
        ProductPurchase::whereIn('id', $ids)->delete();

        return redirect()->route('member.productpurchase.purchaseList', 'other')
            ->with('success', count($ids) . ' purchase(s) deleted successfully.');
    }
}
