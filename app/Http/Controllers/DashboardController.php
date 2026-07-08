<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Equipment;
use App\Models\Booking;
use App\Models\ForumPost;
use App\Models\Harvest;
use App\Notifications\ItemApprovedNotification;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $stats = [
                'active_farmers' => User::where('role', 'petani')->where('is_active', true)->count(),
                'active_sellers' => User::where('role', 'penjual')->where('is_active', true)->count(),
                'approved_products' => Product::where('approval_status', 'approved')->count(),
                'pending_products' => Product::where('approval_status', 'pending')->count(),
                'rejected_products' => Product::where('approval_status', 'rejected')->count(),
                'approved_equipment' => Equipment::where('approval_status', 'approved')->count(),
                'pending_equipment' => Equipment::where('approval_status', 'pending')->count(),
                'total_orders' => Order::count() + Booking::count(),
                'total_posts' => ForumPost::count(),
                'total_users' => User::count(),
            ];

            $pendingProducts = Product::with('user')->where('approval_status', 'pending')->orderBy('created_at', 'desc')->take(7)->get();
            $pendingEquipment = Equipment::with('user')->where('approval_status', 'pending')->orderBy('created_at', 'desc')->take(7)->get();
            $totalPending = $stats['pending_products'] + $stats['pending_equipment'];
            $recentUsers = User::latest()->take(6)->get();

            return view('admin.dashboard', compact('user', 'stats', 'pendingProducts', 'pendingEquipment', 'totalPending', 'recentUsers'));
        }

        if ($user->isPetani()) {
            $myOrders = Order::where('user_id', $user->id)->with(['product', 'seller'])->latest()->get();
            $myBookings = Booking::where('user_id', $user->id)->with(['equipment', 'seller'])->latest()->get();

            return view('farmer.dashboard', compact('user', 'myOrders', 'myBookings'));
        }

        if ($user->isPenyuluh()) {
            $totalEducational = \App\Models\EducationalInfo::count();
            $recentEducational = \App\Models\EducationalInfo::latest()->take(5)->get();

            return view('penyuluh.dashboard', compact('user', 'totalEducational', 'recentEducational'));
        }

        return redirect()->route('profile.edit');
    }

    public function reviewProduct($id)
    {
        $item = Product::with('user')->findOrFail($id);
        return view('admin.review', ['item' => $item, 'type' => 'product']);
    }

    public function reviewEquipment($id)
    {
        $item = Equipment::with('user')->findOrFail($id);
        return view('admin.review', ['item' => $item, 'type' => 'equipment']);
    }

    public function approveProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['approval_status' => 'approved']);
        $product->user->notify(new ItemApprovedNotification($product->name, 'Produk'));
        return redirect()->back()->with('success', 'Produk berhasil disetujui');
    }

    public function rejectProduct($id)
    {
        Product::findOrFail($id)->update(['approval_status' => 'rejected']);
        return redirect()->back()->with('success', 'Produk telah ditolak');
    }

    public function approveEquipment($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->update(['approval_status' => 'approved']);
        $equipment->user->notify(new ItemApprovedNotification($equipment->name, 'Alat'));
        return redirect()->back()->with('success', 'Alat berhasil disetujui');
    }

    public function rejectEquipment($id)
    {
        Equipment::findOrFail($id)->update(['approval_status' => 'rejected']);
        return redirect()->back()->with('success', 'Alat telah ditolak');
    }

    public function markNotificationsAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function manageUsers()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleUserActive($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }
        $user->update(['is_active' => !$user->is_active]);
        return redirect()->back()->with('success', 'Status pengguna berhasil diperbarui.');
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role anda sendiri.');
        }

        $request->validate(['role' => 'required|in:petani,penjual,pembeli,penyuluh,admin']);
        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', "Role {$user->name} berhasil diubah menjadi {$request->role}.");
    }

    public function manageProducts()
    {
        $products = Product::with('user')->latest()->paginate(20);
        return view('admin.products', compact('products'));
    }

    public function manageEquipments()
    {
        $equipments = Equipment::with('user')->latest()->paginate(20);
        return view('admin.equipments', compact('equipments'));
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus oleh admin.');
    }

    public function destroyEquipment($id)
    {
        $equipment = Equipment::findOrFail($id);
        if ($equipment->image_path) {
            Storage::disk('public')->delete($equipment->image_path);
        }
        $equipment->delete();
        return redirect()->back()->with('success', 'Alat berhasil dihapus oleh admin.');
    }
}
