<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view', Role::class);
        $roles = Role::with(['users'])->whereNot('name', 'admin')->latest()->paginate(15);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Role::class);

        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'can_create' => 'nullable|boolean',
            'can_read' => 'nullable|boolean',
            'can_update' => 'nullable|boolean',
            'can_delete' => 'nullable|boolean',
        ]);

        // Ensure unchecked boxes become false
        $data = array_merge([
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false,
        ], $data);

        // Ensure role name is in lowercase
        $data['name'] = strtolower($data['name']);

        Role::updateOrCreate(['id' => $role->id ?? null], $data);

        return redirect()->route('roles.index')->with('ok', 'Role Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);

        // Paginate related models separately
        $users = $role->users()->paginate(5, ['*'], 'users_page');

        return view('roles.show', compact('role', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        return view('roles.edit', [
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'can_create' => 'nullable|boolean',
            'can_read' => 'nullable|boolean',
            'can_update' => 'nullable|boolean',
            'can_delete' => 'nullable|boolean',
        ]);

        // Ensure unchecked boxes become false
        $data = array_merge([
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false,
        ], $data);

        // Ensure role name is in lowercase
        $data['name'] = strtolower($data['name']);
        $role->update($data);

        return redirect()->route('roles.show', $role)->with('ok', 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();

        return redirect()->route('roles.index')->with('ok', 'Deleted');
    }
}
