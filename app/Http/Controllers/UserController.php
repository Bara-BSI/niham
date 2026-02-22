<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Property scoping for non-super-admin
        if (! Auth::user()->isSuperAdmin()) {
            $query->where('property_id', Auth::user()->property_id);
        } else {
            // Super admin: scope to active property if set
            $activePropertyId = session('active_property_id');
            if ($activePropertyId) {
                $query->where('property_id', $activePropertyId);
            }
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        $users = $query->whereNot('name', 'Admin')
            ->where('is_super_admin', false) // don't show super admins in regular list
            ->paginate(15)->withQueryString();
        $departments = Department::all();
        $roles = Role::all();
        $properties = Auth::user()->isSuperAdmin() ? Property::all() : collect();

        return view('users.index', compact('users', 'roles', 'departments', 'properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('users.create', [
            'roles' => Role::all(),
            'departments' => Department::all(),
            'properties' => Auth::user()->isSuperAdmin() ? Property::all() : collect(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:'.User::class],
            'email' => ['string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['required', 'exists:departments,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ];

        // Super admin can assign to any property
        if (Auth::user()->isSuperAdmin()) {
            $rules['property_id'] = ['required', 'exists:properties,id'];
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
            'role_id' => $request->role_id,
        ];

        // Assign property
        if (Auth::user()->isSuperAdmin()) {
            $userData['property_id'] = $request->property_id;
        } else {
            $userData['property_id'] = Auth::user()->property_id;
        }

        $user = User::create($userData);

        event(new Registered($user));

        return redirect()->route('users.show', $user)->with('ok', 'Account Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        $user->load([
            'role',
            'department',
            'property',
        ]);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::all(),
            'departments' => Department::all(),
            'properties' => Auth::user()->isSuperAdmin() ? Property::all() : collect(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'department_id' => ['required', 'exists:departments,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ];

        if (Auth::user()->isSuperAdmin()) {
            $rules['property_id'] = ['required', 'exists:properties,id'];
        }

        $data = $request->validate($rules);

        // Assign property
        if (Auth::user()->isSuperAdmin() && $request->filled('property_id')) {
            $data['property_id'] = $request->property_id;
        }

        $user->update($data);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.show', $user)->with('ok', 'Account Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        return redirect()->route('users.index')->with('ok', 'Deleted');
    }
}
