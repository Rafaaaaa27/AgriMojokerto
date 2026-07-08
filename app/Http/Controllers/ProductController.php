<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:benih,pupuk,pestisida,panen,hasil_panen,obat,alat',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['name', 'category', 'price', 'quantity', 'description']);
        $data['user_id'] = auth()->id();
        $data['approval_status'] = auth()->user()->role === 'petani' ? 'approved' : 'pending';
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        $msg = auth()->user()->role === 'petani'
            ? 'Produk berhasil ditambahkan dan langsung aktif di marketplace.'
            : 'Produk berhasil ditambahkan dan sedang menunggu persetujuan admin.';
        return redirect()->back()->with('success', $msg);
    }

    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:benih,pupuk,pestisida,panen,hasil_panen,obat,alat',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['name', 'category', 'price', 'quantity', 'description']);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
