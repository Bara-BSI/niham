<?php

namespace App\Http\Controllers;

use App\Exports\AssetsExport;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Storage;

class AssetController extends Controller
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
        $query = Asset::query();

        // Search by 'name' or 'tag' fields if a search term is provided
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('tag', 'like', '%'.$searchTerm.'%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Pembatasan akses ke departemen lain (non-admin, non-super-admin)
        if (! Auth::user()->isSuperAdmin() && ! Auth::user()->isRole('admin')) {
            if (! Auth::user()->inDept('EXE') && ! Auth::user()->inDept('PTLP')) {
                $query->where('department_id', Auth::user()->department_id);
            }
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        if ($request->filled('sort')) {
            $query->orderBy($request->sort);
        } else {
            $query->latest();
        }

        $assets = $query->paginate(15)->withQueryString();
        $categories = Category::all();
        $departments = Department::all();

        return view('assets.index', compact('assets', 'categories', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Asset::class);

        return view('assets.create', [
            'categories' => Category::all(),
            'departments' => Department::all(),
            'existingTags' => Asset::select('tag')->distinct()->orderBy('tag')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Asset::class);
        $data = $request->validate([
            'tag' => 'required|string|max:64',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'department_id' => 'required|exists:departments,id',
            'status' => 'in:in_service,out_of_service,disposed',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_duration' => 'in:none,6m,1y,2y,3y',
            'purchase_cost' => 'nullable|numeric',
            'vendor' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:120',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Max 2MB
        ]);
        $data['purchase_cost'] = $request->filled('purchase_cost') ? $request->input('purchase_cost') : null;
        $data['editor'] = Auth::id();

        // Warranty Calculation
        $purchaseDate = $request->filled('purchase_date') ? Carbon::parse($request->purchase_date) : null;
        $warrantyDate = null;

        if ($purchaseDate) {
            switch ($request->warranty_duration) {
                case '6m':
                    $warrantyDate = $purchaseDate->copy()->addMonths(6);
                    break;
                case '1y':
                    $warrantyDate = $purchaseDate->copy()->addYear();
                    break;
                case '2y':
                    $warrantyDate = $purchaseDate->copy()->addYears(2);
                    break;
                case '3y':
                    $warrantyDate = $purchaseDate->copy()->addYears(3);
                    break;
            }
        }

        $data['purchase_date'] = $purchaseDate;
        $data['warranty_date'] = $warrantyDate;

        // property_id is auto-assigned by BelongsToProperty trait
        $asset = Asset::create($data);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');

            $asset->attachments()->create([
                'path' => $path,
                'type' => $file->getClientMimeType(),
            ]);
        }

        return redirect()->route('assets.show', $asset)->with('ok', 'Asset Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        $this->authorize('view', $asset);
        $asset->load([
            'category',
            'department',
            'attachments',
        ]);
        $assetClass = Asset::class;

        return view('assets.show', compact('asset', 'assetClass'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $this->authorize('update', $asset);

        return view('assets.edit', [
            'asset' => $asset,
            'categories' => Category::all(),
            'departments' => Department::all(),
            'existingTags' => Asset::select('tag')->distinct()->orderBy('tag')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'in:in_service,out_of_service,disposed',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_duration' => 'in:none,6m,1y,2y,3y',
            'purchase_cost' => 'nullable|numeric',
            'vendor' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:120',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Max 2MB
        ]);
        $data['purchase_cost'] = $request->filled('purchase_cost') ? $request->input('purchase_cost') : null;
        $data['editor'] = Auth::id();
        // Warranty Calculation
        $purchaseDate = $request->filled('purchase_date') ? Carbon::parse($request->purchase_date) : null;
        $warrantyDate = null;

        if ($purchaseDate) {
            switch ($request->warranty_duration) {
                case '6m':
                    $warrantyDate = $purchaseDate->copy()->addMonths(6);
                    break;
                case '1y':
                    $warrantyDate = $purchaseDate->copy()->addYear();
                    break;
                case '2y':
                    $warrantyDate = $purchaseDate->copy()->addYears(2);
                    break;
                case '3y':
                    $warrantyDate = $purchaseDate->copy()->addYears(3);
                    break;
            }
        }

        $data['purchase_date'] = $purchaseDate;
        $data['warranty_date'] = $warrantyDate;

        $asset->update($data);

        // Attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');

            // If the asset already has an attachment, delete the old file + record
            if ($asset->attachments()->exists()) {
                $oldAttachment = $asset->attachments()->first();

                // Delete the old file from storage
                if ($oldAttachment && \Storage::disk('public')->exists($oldAttachment->path)) {
                    \Storage::disk('public')->delete($oldAttachment->path);
                }

                // Update the existing record instead of creating a new one
                $oldAttachment->update([
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                ]);
            } else {
                // If no attachment exists, just create a new one
                $asset->attachments()->create([
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->route('assets.show', $asset)->with('ok', 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);
        // Delete the image if it exists
        if ($asset->attachments && $asset->attachments->path) {
            $path = $asset->attachments->path;

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $asset->attachments->delete();
        }
        $asset->forceDelete(); // Hard delete

        return redirect()->route('assets.index')->with('ok', 'Deleted');
    }

    public function export(Request $request)
    {
        $query = Asset::query();

        // Search
        // Search by 'name' or 'tag' fields if a search term is provided
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('tag', 'like', '%'.$searchTerm.'%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Pembatasan akses ke departemen lain (non-admin, non-super-admin)
        if (! Auth::user()->isSuperAdmin() && ! Auth::user()->isRole('admin')) {
            if (! Auth::user()->inDept('EXE') && ! Auth::user()->inDept('PTLP')) {
                $query->where('department_id', Auth::user()->department_id);
            }
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        if ($request->filled('sort')) {
            $query->orderBy($request->sort);
        } else {
            $query->latest();
        }

        $assetsToExport = $query->get();

        return Excel::download(new AssetsExport($assetsToExport), 'assets.xlsx');
    }
}
