<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Equipment;
use App\Models\Booking;
use App\Models\Order;

class BookingController extends Controller
{
    public function checkout($type, $id)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('marketplace.index')->with('error', 'Admin hanya dapat melihat produk tanpa melakukan pemesanan.');
        }

        $item = match ($type) {
            'product' => Product::findOrFail($id),
            'equipment' => Equipment::findOrFail($id),
            default => abort(404),
        };

        return view('checkout.checkout', compact('type', 'item'));
    }

    public function process(Request $request)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('marketplace.index')->with('error', 'Admin tidak diizinkan untuk melakukan pemesanan.');
        }

        $request->validate([
            'type' => 'required|in:product,equipment',
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:20',
            'shipping_address' => 'required_if:type,product',
            'booking_date' => 'required_if:type,equipment|date',
        ]);

        $type = $request->type;
        $id = $request->item_id;
        $qty = $request->quantity;

        if ($type === 'product') {
            $product = Product::findOrFail($id);
            if ($product->quantity < $qty) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
            }

            Order::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'seller_id' => $product->user_id,
                'quantity' => $qty,
                'total_price' => $product->price * $qty,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'buyer_name' => $request->buyer_name,
                'buyer_phone' => $request->buyer_phone,
            ]);

            $product->decrement('quantity', $qty);

            return redirect()->route('checkout.success')->with('success', 'pesanan-berhasil');
        }

        if ($type === 'equipment') {
            $equipment = Equipment::findOrFail($id);
            if ($equipment->quantity < $qty) {
                return redirect()->back()->with('error', 'Ketersediaan alat tidak mencukupi.');
            }

            Booking::create([
                'equipment_id' => $equipment->id,
                'user_id' => auth()->id(),
                'seller_id' => $equipment->user_id,
                'quantity' => $qty,
                'total_price' => $equipment->price * $qty,
                'status' => 'pending',
                'booking_date' => $request->booking_date,
                'buyer_name' => $request->buyer_name,
                'buyer_phone' => $request->buyer_phone,
            ]);

            $equipment->decrement('quantity', $qty);

            return redirect()->route('checkout.success')->with('success', 'penyewaan-berhasil');
        }

        abort(400);
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $order = Order::where('id', $id)->where('seller_id', auth()->id())->with('product')->firstOrFail();

        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            $order->product->increment('quantity', $order->quantity);
        }

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking = Booking::where('id', $id)->where('seller_id', auth()->id())->with('equipment')->firstOrFail();

        if ($request->status === 'cancelled' && $booking->status !== 'cancelled') {
            $booking->equipment->increment('quantity', $booking->quantity);
        }

        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status penyewaan berhasil diperbarui.');
    }

    // ========== BUYER ACTIONS ==========

    public function cancelOrder($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('product')
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan yang sudah diproses tidak dapat dibatalkan.');
        }

        $order->product->increment('quantity', $order->quantity);
        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function confirmOrder($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Pesanan sudah dikonfirmasi sebelumnya.');
        }

        $order->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Pesanan telah diterima. Terima kasih!');
    }

    public function cancelBooking($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('equipment')
            ->firstOrFail();

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Penyewaan yang sudah diproses tidak dapat dibatalkan.');
        }

        $booking->equipment->increment('quantity', $booking->quantity);
        $booking->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Penyewaan berhasil dibatalkan.');
    }

    public function confirmBooking($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($booking->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Penyewaan sudah dikonfirmasi sebelumnya.');
        }

        $booking->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Penyewaan selesai. Terima kasih!');
    }
}
