<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ── List Page ──────────────────────────────────────────────
    public function product()
    {
        $products   = Product::with(['category', 'subcategory'])->get();
        $categories = Category::where('status', 1)
                               ->whereNull('parent_id')
                               ->get();
        return view('admin.product', compact('products', 'categories'));
    }

    // ── Store ──────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'category_id'    => 'required|exists:category,id',
            'subcategory_id' => 'nullable|exists:category,id',
            'name'           => 'required|string|max:255',
            'smart_points'   => 'required|numeric|min:0',
            'base_price'     => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('_token', 'image');

        // Auto-calculate qty: smart_points × 0.001
        $data['qty'] = round($request->smart_points * 0.001, 4);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Create product first to get auto-increment ID
        $product = Product::create($data);

        // Auto-generate HSN: "HSN" + 7-digit zero-padded ID  (e.g. HSN0000005)
        $product->hsn_code = 'HSN' . str_pad($product->id, 7, '0', STR_PAD_LEFT);
        $product->save();

        return redirect()->route('product')->with('success', 'Product added successfully.');
    }

    // ── Update ─────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id'    => 'required|exists:category,id',
            'subcategory_id' => 'nullable|exists:category,id',
            'name'           => 'required|string|max:255',
            'smart_points'   => 'required|numeric|min:0',
            'base_price'     => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('_token', '_method', 'image');

        // Auto-calculate qty: smart_points × 0.001
        $data['qty'] = round($request->smart_points * 0.001, 4);

        // HSN never changes on update — it's always fixed to the product ID
        // No need to touch hsn_code here

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('product')->with('success', 'Product updated successfully.');
    }

    // ── Delete ─────────────────────────────────────────────────
    public function delete($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('product')->with('success', 'Product deleted successfully.');
    }

    // ── Toggle Status ──────────────────────────────────────────
    public function toggleStatus($id)
    {
        $product         = Product::findOrFail($id);
        $product->status = $product->status == 1 ? 0 : 1;
        $product->save();

        return response()->json(['status' => $product->status]);
    }

    // ── AJAX: Get Subcategories ────────────────────────────────
    public function getSubcategories($categoryId)
    {
        $subcategories = Category::where('parent_id', $categoryId)
                                  ->where('status', 1)
                                  ->get(['id', 'name']);
        return response()->json($subcategories);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('product')->with('error', 'No products selected.');
        }

        foreach ($ids as $id) {
            $product = Product::find($id);
            if ($product) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->delete();
            }
        }

        return redirect()->route('product')->with('success', count($ids) . ' product(s) deleted successfully.');
    }
}
