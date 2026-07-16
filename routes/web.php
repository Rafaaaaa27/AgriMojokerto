<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ForumPostController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\EducationalInfoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard — role-based routing inside controller
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/product/{id}/review', [DashboardController::class, 'reviewProduct'])->name('admin.product.review');
    Route::get('/equipment/{id}/review', [DashboardController::class, 'reviewEquipment'])->name('admin.equipment.review');
    Route::post('/product/{id}/approve', [DashboardController::class, 'approveProduct'])->name('admin.product.approve');
    Route::post('/product/{id}/reject', [DashboardController::class, 'rejectProduct'])->name('admin.product.reject');
    Route::post('/equipment/{id}/approve', [DashboardController::class, 'approveEquipment'])->name('admin.equipment.approve');
    Route::post('/equipment/{id}/reject', [DashboardController::class, 'rejectEquipment'])->name('admin.equipment.reject');
    Route::get('/users', [DashboardController::class, 'manageUsers'])->name('admin.users.index');
    Route::post('/users/{id}/toggle-active', [DashboardController::class, 'toggleUserActive'])->name('admin.users.toggle');
    Route::patch('/users/{id}/role', [DashboardController::class, 'updateUserRole'])->name('admin.users.role');
    Route::get('/products', [DashboardController::class, 'manageProducts'])->name('admin.products.index');
    Route::delete('/products/{id}', [DashboardController::class, 'destroyProduct'])->name('admin.products.destroy');
    Route::get('/equipments', [DashboardController::class, 'manageEquipments'])->name('admin.equipments.index');
    Route::delete('/equipments/{id}', [DashboardController::class, 'destroyEquipment'])->name('admin.equipments.destroy');
});

// Public Marketplace & Equipment browsing
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipments.index');

// Forum — public view, login required to post
Route::middleware(['auth', 'role:petani,pembeli,admin,penyuluh'])->group(function () {
    Route::get('/forum', [ForumPostController::class, 'index'])->name('forum.index');
    Route::post('/forum', [ForumPostController::class, 'store'])->name('forum.store');
    Route::post('/forum/{post}/reply', [ForumPostController::class, 'reply'])->name('forum.reply');
    Route::put('/forum/{post}', [ForumPostController::class, 'update'])->name('forum.update');
    Route::delete('/forum/{post}', [ForumPostController::class, 'destroy'])->name('forum.destroy');
    Route::post('/forum/{post}/like', [ForumPostController::class, 'toggleLike'])->name('forum.like');
    Route::post('/forum/comment/{comment}/like', [ForumPostController::class, 'toggleCommentLike'])->name('forum.comment.like');
    Route::delete('/forum/comment/{comment}', [ForumPostController::class, 'deleteComment'])->name('forum.comment.delete');
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::match(['post', 'delete'], '/profile/photo/delete', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/library', [EducationalInfoController::class, 'index'])->name('library.index');
    Route::get('/library/{id}', [EducationalInfoController::class, 'show'])->name('library.show');
    Route::get('/library/{id}/download', [EducationalInfoController::class, 'download'])->name('library.download');

    Route::get('/checkout/{type}/{id}', [BookingController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [BookingController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [BookingController::class, 'success'])->name('checkout.success');

    Route::post('/notifications/mark-as-read', [DashboardController::class, 'markNotificationsAsRead'])->name('notifications.markAsRead');

    // Buyer order actions
    Route::patch('/buyer/order/{id}/cancel', [BookingController::class, 'cancelOrder'])->name('buyer.order.cancel');
    Route::patch('/buyer/order/{id}/confirm', [BookingController::class, 'confirmOrder'])->name('buyer.order.confirm');
    Route::patch('/buyer/booking/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('buyer.booking.cancel');
    Route::patch('/buyer/booking/{id}/confirm', [BookingController::class, 'confirmBooking'])->name('buyer.booking.confirm');
});

// Seller/Petani product & equipment management
Route::middleware(['auth', 'role:penjual,petani,admin'])->group(function () {
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::post('/equipments/store', [EquipmentController::class, 'store'])->name('equipments.store');
    Route::put('/equipments/{equipment}', [EquipmentController::class, 'update'])->name('equipments.update');
    Route::delete('/equipments/{equipment}', [EquipmentController::class, 'destroy'])->name('equipments.destroy');

    Route::patch('/seller/order/{id}', [BookingController::class, 'updateOrderStatus'])->name('seller.order.update');
    Route::patch('/seller/booking/{id}', [BookingController::class, 'updateBookingStatus'])->name('seller.booking.update');
});

// Petani-specific: harvest & schedule
Route::middleware(['auth', 'role:petani,penyuluh,admin'])->group(function () {
    Route::get('/harvest', [HarvestController::class, 'index'])->name('harvest.index');
    Route::post('/harvests', [HarvestController::class, 'store'])->name('harvests.store');
    Route::patch('/harvests/{harvest}', [HarvestController::class, 'update'])->name('harvests.update');
    Route::delete('/harvests/{harvest}', [HarvestController::class, 'destroy'])->name('harvests.destroy');

    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::patch('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // Farming Cycle routes
    Route::post('/farming-cycles', [ScheduleController::class, 'storeCycle'])->name('farming-cycles.store');
    Route::delete('/farming-cycles/{cycle}', [ScheduleController::class, 'destroyCycle'])->name('farming-cycles.destroy');
    Route::patch('/farming-cycles/{cycle}/complete', [ScheduleController::class, 'completeCycle'])->name('farming-cycles.complete');

    // Stage routes
    Route::patch('/schedule-stages/{stage}', [ScheduleController::class, 'updateStage'])->name('schedule-stages.update');

    // Schedule Item routes
    Route::post('/schedule-stages/{stage}/items', [ScheduleController::class, 'storeItem'])->name('schedule-items.store');
    Route::patch('/schedule-items/{item}', [ScheduleController::class, 'updateItem'])->name('schedule-items.update');
    Route::patch('/schedule-items/{item}/date', [ScheduleController::class, 'updateItemDate'])->name('schedule-items.date');
    Route::patch('/schedule-items/{item}/stage', [ScheduleController::class, 'updateItemStage'])->name('schedule-items.stage');
    Route::delete('/schedule-items/{item}', [ScheduleController::class, 'destroyItem'])->name('schedule-items.destroy');
});

// Market price data API (semua role bisa lihat)
Route::middleware('auth')->group(function () {
    Route::get('/api/market-prices/{commodity}', [\App\Http\Controllers\MarketPriceController::class, 'data'])->name('api.market-prices');
    Route::get('/api/market-prices', [\App\Http\Controllers\MarketPriceController::class, 'latest'])->name('api.market-prices.latest');
});

// Penyuluh: manage market prices
Route::middleware(['auth', 'role:penyuluh,admin'])->prefix('admin')->group(function () {
    Route::get('/market-prices', [\App\Http\Controllers\MarketPriceController::class, 'index'])->name('admin.market-prices.index');
    Route::post('/market-prices', [\App\Http\Controllers\MarketPriceController::class, 'store'])->name('admin.market-prices.store');
    Route::patch('/market-prices/{marketPrice}', [\App\Http\Controllers\MarketPriceController::class, 'update'])->name('admin.market-prices.update');
    Route::delete('/market-prices/{marketPrice}', [\App\Http\Controllers\MarketPriceController::class, 'destroy'])->name('admin.market-prices.destroy');
});

// Penyuluh: educational content management
Route::middleware(['auth', 'role:penyuluh,admin'])->group(function () {
    Route::get('/educational/manage', [EducationalInfoController::class, 'manage'])->name('educational.manage');
    Route::post('/educational-infos', [EducationalInfoController::class, 'store'])->name('educational.store');
    Route::patch('/educational-infos/{educationalInfo}', [EducationalInfoController::class, 'update'])->name('educational.update');
    Route::delete('/educational-infos/{educationalInfo}', [EducationalInfoController::class, 'destroy'])->name('educational.destroy');
});

require __DIR__.'/auth.php';
