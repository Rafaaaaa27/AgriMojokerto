<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Harvest;

class HarvestController extends Controller
{
    public function index()
    {
        $harvests = Harvest::where('user_id', auth()->id())->latest()->get();
        $harvestsJson = $harvests->map(fn($h) => [
            'id' => $h->id,
            'crop_type' => $h->crop_type,
            'harvest_date' => $h->harvest_date->format('Y-m-d'),
            'quantity' => $h->quantity,
            'unit' => $h->unit,
            'notes' => $h->notes,
        ])->keyBy('id');
        return view('harvest.index', compact('harvests', 'harvestsJson'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'crop_type' => 'required|string|max:255',
            'harvest_date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:10',
            'notes' => 'nullable|string',
        ]);

        Harvest::create([
            'user_id' => auth()->id(),
            'crop_type' => $request->crop_type,
            'harvest_date' => $request->harvest_date,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Data panen berhasil disimpan.');
    }

    public function update(Request $request, Harvest $harvest)
    {
        if ($harvest->user_id !== auth()->id()) abort(403);

        $request->validate([
            'crop_type' => 'required|string|max:255',
            'harvest_date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:10',
            'notes' => 'nullable|string',
        ]);

        $harvest->update($request->only('crop_type', 'harvest_date', 'quantity', 'unit', 'notes'));

        return redirect()->back()->with('success', 'Data panen berhasil diperbarui.');
    }

    public function destroy(Harvest $harvest)
    {
        if ($harvest->user_id !== auth()->id()) abort(403);
        $harvest->delete();
        return redirect()->back()->with('success', 'Data panen berhasil dihapus.');
    }
}
