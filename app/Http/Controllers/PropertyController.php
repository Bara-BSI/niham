<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of properties.
     */
    public function index()
    {
        $this->authorize('viewAny', Property::class);
        $properties = Property::withCount(['users', 'assets', 'departments', 'categories'])
            ->orderBy('name')
            ->paginate(15);

        return view('properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        $this->authorize('create', Property::class);

        return view('properties.create');
    }

    /**
     * Store a newly created property.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Property::class);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:properties,code',
            'address' => 'nullable|string|max:500',
            'accent_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|max:2048',
            'background_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('branding', 'public');
        }
        if ($request->hasFile('background_image')) {
            $data['background_image_path'] = $request->file('background_image')->store('branding', 'public');
        }

        $data['code'] = strtoupper($data['code']);

        Property::create($data);

        return redirect()->route('properties.index')->with('ok', 'Property Created');
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        $this->authorize('view', $property);
        $property->loadCount(['users', 'assets', 'departments', 'categories']);

        $users = $property->users()->with('role')->paginate(10, ['*'], 'users_page');
        $departments = $property->departments()->paginate(10, ['*'], 'depts_page');

        return view('properties.show', compact('property', 'users', 'departments'));
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        return view('properties.edit', compact('property'));
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:properties,code,'.$property->id,
            'address' => 'nullable|string|max:500',
            'accent_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|max:2048',
            'background_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            if ($property->logo_path) \Illuminate\Support\Facades\Storage::disk('public')->delete($property->logo_path);
            $data['logo_path'] = $request->file('logo')->store('branding', 'public');
        }
        if ($request->hasFile('background_image')) {
            if ($property->background_image_path) \Illuminate\Support\Facades\Storage::disk('public')->delete($property->background_image_path);
            $data['background_image_path'] = $request->file('background_image')->store('branding', 'public');
        }

        $data['code'] = strtoupper($data['code']);

        $property->update($data);

        return redirect()->route('properties.show', $property)->with('ok', 'Property Updated');
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);
        $property->delete();

        return redirect()->route('properties.index')->with('ok', 'Property Deleted');
    }

    /**
     * Switch active property for super admin (stored in session).
     */
    public function switchProperty(Request $request)
    {
        $request->validate([
            'property_id' => 'nullable|exists:properties,id',
        ]);

        if ($request->property_id) {
            session(['active_property_id' => (int) $request->property_id]);
        } else {
            session()->forget('active_property_id');
        }

        return back()->with('ok', $request->property_id
            ? 'Switched to '.Property::find($request->property_id)->name
            : 'Viewing all properties');
    }
}
