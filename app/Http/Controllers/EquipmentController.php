<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::where('is_available', true)
                        ->where('approval_status', 'approved');

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('crop') && $request->crop !== 'all') {
            $query->where('crop_type', $request->crop);
        }

        if ($request->filled('location') && $request->location !== 'all') {
            $query->where('location', 'like', '%' . $this->escapeLike($request->location) . '%');
        }

        $equipments = $query->latest()->paginate(12)->withQueryString();

        return view('equipments.index', compact('equipments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'location' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['name', 'type', 'price', 'unit', 'quantity', 'location', 'phone', 'description']);
        $data['user_id'] = auth()->id();
        $data['approval_status'] = auth()->user()->role === 'petani' ? 'approved' : 'pending';
        $data['is_available'] = true;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('equipments', 'public');
        }

        Equipment::create($data);

        $msg = auth()->user()->role === 'petani'
            ? 'Alat berhasil ditambahkan dan langsung aktif di marketplace.'
            : 'Alat berhasil ditambahkan dan sedang menunggu persetujuan admin.';
        return redirect()->back()->with('success', $msg);
    }

    public function update(Request $request, Equipment $equipment)
    {
        if ($equipment->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'location' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['name', 'type', 'price', 'unit', 'quantity', 'location', 'phone', 'description']);

        if ($request->hasFile('image')) {
            if ($equipment->image_path) {
                Storage::disk('public')->delete($equipment->image_path);
            }
            $data['image_path'] = $request->file('image')->store('equipments', 'public');
        }

        $equipment->update($data);

        return redirect()->back()->with('success', 'Alat berhasil diperbarui.');
    }

    public function destroy(Equipment $equipment)
    {
        if ($equipment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($equipment->image_path) {
            Storage::disk('public')->delete($equipment->image_path);
        }
        $equipment->delete();

        return redirect()->back()->with('success', 'Alat berhasil dihapus.');
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
