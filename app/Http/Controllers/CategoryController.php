<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')->get();

        return view('categories.index')->with([
            'categories'  => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'name'      => 'required|min:3|max:255|string',
            'parent_id' => 'sometimes|nullable|numeric'
        ]);

        $parent = Category::where('id', $request->input('parent_id'))->first();

        Category::create($validatedData + ['grandparent_id' => $parent->parent_id]);

        return redirect()->route('category.index')->withSuccess('You have successfully created a Category!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category = Category::findOrFail($category->id);

        if ($category->products->isNotEmpty()) {
            flash('Impossible! There are some products belonging to this category')->error();
            return redirect()->route('category.index');
        }

        if ($category->children) {
            foreach ($category->children as $child) {
                if ($child->products->isNotEmpty()) {
                    flash('Impossible! There are some products belonging to this branch of categories')->error();
                    return redirect()->route('category.index');
                }
                if ($child->children) {
                    foreach ($child->children as $grandchild) {
                        if ($grandchild->products->isNotEmpty()) {
                            flash('Impossible! There are some products belonging to this branch of categories')->error();  // phpcs:ignore
                            return redirect()->route('category.index');
                        }
                    }
                    $child->children()->delete();
                }
            }
            $category->children()->delete();
        }

        $category->delete();

        flash('Success')->success();
        return redirect()->route('category.index');
    }
}
