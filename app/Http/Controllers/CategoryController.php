<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function category()
{
    $categories = Category::whereNull('parent_id')
                    ->with(['subcategories' => function($q) {
                        $q->orderBy('name', 'asc');
                    }])
                    ->orderBy('name', 'asc')
                    ->get();

    $parentOptions = Category::whereNull('parent_id')->orderBy('name')->get();

    // Build flat rows like Excel view
    $rows = [];
    $counter = 1;
    foreach ($categories as $cat) {
        // Parent row (no subcategory)
        $rows[] = [
            'serial'      => $counter++,
            'category'    => $cat->name,
            'subcategory' => '-',
            'status'      => $cat->status,
            'id'          => $cat->id,
            'parent_id'   => null,
            'is_parent'   => true,
        ];

        // Each subcategory as its own row
        foreach ($cat->subcategories as $sub) {
            $rows[] = [
                'serial'      => $counter++,
                'category'    => $cat->name,
                'subcategory' => $sub->name,
                'status'      => $sub->status,
                'id'          => $sub->id,
                'parent_id'   => $sub->parent_id,
                'is_parent'   => false,
            ];
        }
    }

    return view('admin.category', compact('rows', 'parentOptions'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:category,id',
        ]);

        Category::create([
            'name'      => $request->name,
            'parent_id' => $request->parent_id ?: null,
            'status'    => 1,
        ]);

        return redirect()->route('category')->with('success', 'Record added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:category,id',
        ]);

        $category = Category::findOrFail($id);

        if ($request->parent_id == $id) {
            return redirect()->route('category')->with('error', 'A category cannot be its own parent.');
        }

        $category->update([
            'name'      => $request->name,
            'parent_id' => $request->parent_id ?: null,
        ]);

        return redirect()->route('category')->with('success', 'Record updated successfully!');
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        // Delete all subcategories first
        Category::where('parent_id', $id)->delete();
        $category->delete();
        return redirect()->route('category')->with('success', 'Record deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();

        return response()->json([
            'status'  => $category->status,
            'label'   => $category->status == 1 ? 'Active' : 'Inactive',
            'message' => 'Status updated successfully!'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('category.index')
                ->with('error', 'No categories selected.');
        }

        // Delete subcategories first (children), then parents
        Category::whereIn('id', $ids)->delete();

        return redirect()->route('category.index')
            ->with('success', count($ids) . ' record(s) deleted successfully.');
    }
}