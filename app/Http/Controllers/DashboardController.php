<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalAssets'       => Asset::count(),
            'isAssets'          => Asset::where('status', 'in_service')->count(),
            'oosAssets'         => Asset::where('status', 'out_of_service')->count(),
            'disposedAssets'    => Asset::where('status', 'disposed')->count(),
            'totalValue'        => Asset::sum('purchase_cost'),
            'assetsByDepartment'=> Asset::with('department')
                                        ->get()
                                        ->groupBy(fn($a) => $a->department->name)
                                        ->map->count(),
            'recentAssets'      => Asset::with('department')->orderByDesc('updated_at')->take(5)->get(),
        ]);
    }
}
