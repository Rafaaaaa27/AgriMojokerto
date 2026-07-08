<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Equipment;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
                        ->where('approval_status', 'approved')
                        ->with('user');

        if ($request->filled('search')) {
            $search = $this->escapeLike($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('categories')) {
            $query->whereIn('category', $request->categories);
        }

        match ($request->get('sort', 'latest')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();

        $eqQuery = Equipment::where('is_available', true)
                               ->where('approval_status', 'approved')
                               ->with('user');

        if ($request->filled('search')) {
            $search = $this->escapeLike($request->search);
            $eqQuery->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $equipments = $eqQuery->latest()->get();

        return view('marketplace.index', compact('products', 'equipments'));
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
