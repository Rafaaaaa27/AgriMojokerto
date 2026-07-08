<?php

namespace App\Http\Controllers;

use App\Models\MarketPrice;
use Illuminate\Http\Request;

class MarketPriceController extends Controller
{
    const COMMODITIES = ['padi'];

    const COMMODITY_LABELS = [
        'padi' => 'Padi',
    ];

    const COMMODITY_COLORS = [
        'padi' => '#34d399',
    ];

    const COMMODITY_ICONS = [
        'padi' => '🌾',
    ];

    public function index()
    {
        $data = [];
        foreach (self::COMMODITIES as $key) {
            $data[$key . 'Prices'] = MarketPrice::where('commodity', $key)
                ->orderBy('date', 'desc')
                ->paginate(15, ['*'], $key . '_page');
        }

        $view = auth()->user()?->isPenyuluh()
            ? 'penyuluh.market-prices'
            : 'admin.market-prices';

        return view($view, array_merge($data, [
            'commodities' => self::COMMODITIES,
            'labels' => self::COMMODITY_LABELS,
            'colors' => self::COMMODITY_COLORS,
        ]));
    }

    public function store(Request $request)
    {
        $valid = implode(',', self::COMMODITIES);

        $request->validate([
            'commodity' => 'required|in:' . $valid,
            'price' => 'required|integer|min:0',
            'date' => 'required|date',
            'source' => 'nullable|string|max:255',
        ]);

        MarketPrice::create($request->only('commodity', 'price', 'date', 'source'));

        $label = self::COMMODITY_LABELS[$request->commodity] ?? $request->commodity;

        return redirect()->route('admin.market-prices.index')->with('success', "Harga {$label} berhasil ditambahkan.");
    }

    public function update(Request $request, MarketPrice $marketPrice)
    {
        $request->validate([
            'price' => 'required|integer|min:0',
            'date' => 'required|date',
            'source' => 'nullable|string|max:255',
        ]);

        $marketPrice->update($request->only('price', 'date', 'source'));

        return redirect()->route('admin.market-prices.index')->with('success', 'Harga berhasil diperbarui.');
    }

    public function destroy(MarketPrice $marketPrice)
    {
        $marketPrice->delete();

        return redirect()->route('admin.market-prices.index')->with('success', 'Harga berhasil dihapus.');
    }

    public function data($commodity)
    {
        $prices = MarketPrice::where('commodity', $commodity)
            ->orderBy('date')
            ->get(['date', 'price']);

        $latest = $prices->last();
        $prev = $prices->count() >= 7 ? $prices->get($prices->count() - 7) : null;

        $change = 0;
        $changeLabel = '0%';
        if ($latest && $prev && $prev->price > 0) {
            $pct = round((($latest->price - $prev->price) / $prev->price) * 100, 1);
            $change = $pct;
            $changeLabel = ($pct >= 0 ? '+' : '') . $pct . '%';
        }

        return response()->json([
            'labels' => $prices->pluck('date'),
            'prices' => $prices->pluck('price'),
            'latest' => $latest?->price ?? 0,
            'change' => $change,
            'changeLabel' => $changeLabel,
        ]);
    }

    public function latest()
    {
        $result = [];
        foreach (self::COMMODITIES as $key) {
            $latest = MarketPrice::where('commodity', $key)->latest('date')->first();
            $result[$key] = $latest?->price ?? 0;
        }

        return response()->json($result);
    }
}
