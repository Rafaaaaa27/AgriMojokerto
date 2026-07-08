<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Equipment;
use App\Models\Order;
use App\Models\Booking;
use App\Models\EducationalInfo;
use App\Models\Harvest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $menu = $request->get('menu', 'dashboard');

        $myProducts = Product::where('user_id', $user->id)->get();
        $myEquipments = Equipment::where('user_id', $user->id)->get();

        $incomingOrders = Order::where('seller_id', $user->id)->with(['product', 'user'])->latest()->get();
        $incomingBookings = Booking::where('seller_id', $user->id)->with(['equipment', 'user'])->latest()->get();

        $myOrders = Order::where('user_id', $user->id)->with(['product', 'seller'])->latest()->get();
        $myBookings = Booking::where('user_id', $user->id)->with(['equipment', 'seller'])->latest()->get();

        $myEducationalInfos = EducationalInfo::where('user_id', $user->id)->latest()->get();

        $stats = [
            'my_products_count' => $myProducts->count(),
            'approved_listings' => $myProducts->where('approval_status', 'approved')->count() + $myEquipments->where('approval_status', 'approved')->count(),
            'pending_listings' => $myProducts->where('approval_status', 'pending')->count() + $myEquipments->where('approval_status', 'pending')->count(),
            'sales_count' => Order::where('seller_id', $user->id)->where('status', 'completed')->count(),
            'active_bookings' => Booking::where('seller_id', $user->id)->where('status', 'pending')->count(),
            'revenue' => Order::where('seller_id', $user->id)->where('status', 'completed')->sum('total_price'),
            'my_orders_count' => $myOrders->count(),
            'my_bookings_count' => $myBookings->count(),
            'orders_count' => $myOrders->count() + $myBookings->count(),
            'educational_count' => $myEducationalInfos->count(),
            'total_educational_views' => $myEducationalInfos->sum('views'),
            'total_harvest_quantity' => Harvest::where('user_id', $user->id)->sum('quantity'),
        ];

        return view('profile.edit', compact(
            'user', 'menu', 'myProducts', 'myEquipments',
            'incomingOrders', 'incomingBookings', 'myOrders', 'myBookings',
            'stats', 'myEducationalInfos'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $request->user()->fill($request->only('name', 'email', 'phone', 'city', 'address'));

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit', ['menu' => 'settings'])->with('success', 'Profil berhasil diperbarui.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
