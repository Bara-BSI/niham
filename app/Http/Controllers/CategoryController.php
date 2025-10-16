<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Extra security redundancy
    public function __construct() {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view', Category::class);
        $categories = Category::with(['assets'])->orderBy('name')->paginate(15);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Category::class);
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255'
        ]);

        // Ensure Category name in upper case
        $data['name']=strtoupper($data['name']);
        $data['code']=strtoupper($data['code']);

        Category::updateOrCreate(['id' => $category->id ?? null], $data);
        return redirect()->route('categories.index')->with('ok','Category Created');
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        $assets = $category->assets()->paginate(5, ['*'], 'assets_page');

        return view('categories.show', compact('category', 'assets'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255'
        ]);

        // Ensure Category name in upper case
        $data['name']=strtoupper($data['name']);
        $data['code']=strtoupper($data['code']);

        $category->update($data);
        return redirect()->route('categories.show', $category)->with('ok','Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return redirect()->route('categories.index')->with('ok','Deleted');
    }
}
