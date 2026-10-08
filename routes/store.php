<?php

use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\CatalogController;
use App\Http\Controllers\Site\DomainSearchController;
use App\Http\Controllers\Site\LicenseController;
use App\Http\Controllers\Site\PremiumDomainController;
use App\Models\ProductType;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Store Routes (portal client)
|--------------------------------------------------------------------------
| Katalog produk, penjualan domain/lisensi, dan keranjang. Toko berjalan di
| domain client (member.*) di bawah prefix /store -- satu domain dengan login,
| keranjang (session), dan checkout, sehingga alur order tidak lagi
| berpindah domain. Prefix dipasang di routes/web.php; nama route
| (catalog.*, cart.*, ...) tidak berubah. Tamu boleh menjelajah toko; login
| baru diminta saat checkout. Domain publik hanya memuat konten CMS.
*/
// Segmen {section} = slug jenis produk (tabel product_types), bukan daftar tetap.
$catalogSections = ProductType::sectionPattern();

Route::controller(CatalogController::class)->group(function () use ($catalogSections) {
    Route::get('hosting', 'indexBootstrap')->name('catalog.index');
    Route::get('vps', 'vpsBootstrap')->name('catalog.vps');
    Route::get('{section}/{category}', 'categoryBootstrap')
        ->name('catalog.category')
        ->where('section', $catalogSections);
    Route::get('{section}/{category}/{product}', 'productBootstrap')
        ->name('catalog.product')
        ->where('section', $catalogSections);
});

Route::controller(LicenseController::class)->prefix('lisensi')->name('license.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('{slug}', 'show')->name('show');
});

Route::controller(DomainSearchController::class)->group(function () {
    Route::get('cek-domain', 'searchBootstrap')->name('domain.search');
    Route::post('cek-domain/keranjang', 'addToCart')->name('domain.add-to-cart');
    Route::get('transfer-domain', 'transferFormBootstrap')->name('domains.transfer');
    Route::post('transfer-domain', 'submitTransfer')->name('domains.transfer.submit');
});

Route::controller(PremiumDomainController::class)->prefix('domain-premium')->name('domain-premium.')->group(function () {
    Route::get('/', 'indexBootstrap')->name('index');
    Route::post('cek', 'check')->name('check');
});

Route::controller(CartController::class)->prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', 'indexBootstrap')->name('index');
    Route::post('produk', 'addProduct')->name('add-product');
    Route::post('lisensi', 'addAddon')->name('add-addon');
    Route::post('domain-premium', 'addPremiumDomain')->name('add-premium-domain');
    Route::post('domain-premium-custom', 'addCustomPremium')->name('add-custom-premium');
    Route::post('update-siklus', 'updateProductCycle')->name('update-cycle');
    Route::post('update-tahun', 'updateDomainYears')->name('update-years');
    Route::post('privacy', 'toggleWhoisPrivacy')->name('toggle-privacy');
    Route::post('hapus', 'remove')->name('remove');
    Route::post('kosongkan', 'clear')->name('clear');
});
