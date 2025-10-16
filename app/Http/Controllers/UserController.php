<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }
        
        $users = $query->whereNot('name','Admin')->paginate(15)->withQueryString();
        $departments = Department::all();
        $roles = Role::all();
        return view('users.index', compact('users','roles','departments'));
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
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:'.User::class],
            'email' => ['string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['required','exists:departments,id'],
            'role_id' => ['required','exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
            'role_id' => $request->role_id
        ]);

        event(new Registered($user));

        return redirect()->route('users.show', $user)->with('ok','Account Created');
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
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'department_id' => ['required','exists:departments,id'],
            'role_id' => ['required','exists:roles,id'],
        ]);

        $user->update($data);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }


        return redirect()->route('users.show', $user)->with('ok','Account Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        // // Delete the image if it exists
        // if ($user->attachments && $user->attachments->path) {
        //     $path = $user->attachments->path;

        //     if (Storage::disk('public')->exists($path)) {
        //         Storage::disk('public')->delete($path);
        //     }

        //     $user->attachments->delete();
        // }
        $user->delete();
        return redirect()->route('users.index')->with('ok','Deleted');
    }
}
