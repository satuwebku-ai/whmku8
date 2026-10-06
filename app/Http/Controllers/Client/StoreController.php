<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Services\Cart\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Toko di dalam area client: klien yang sudah login bisa memilih kategori,
 * melihat paket, dan menaruhnya ke keranjang tanpa pindah ke situs publik.
 * Keranjang memakai CartService yang sama dengan situs publik (session),
 * jadi checkout (Client\CheckoutController) tidak berubah.
 */
class StoreController extends Controller
{
    public function index(Request $request): View
    {
        $type = in_array($request->query('type'), ['hosting', 'vps'], true) ? $request->query('type') : null;
        $search = trim((string) $request->query('q', ''));

        $categories = ProductGroup::active()
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('products', fn ($p) => $p->active()->where('name', 'like', "%{$search}%"))))
            ->with(['products' => fn ($q) => $q->active()])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->filter(fn ($cat) => $cat->products->isNotEmpty());

        return view('client.store.index', [
            'categories' => $categories,
            'type' => $type,
            'search' => $search,
        ]);
    }

    public function category(string $slug): View
    {
        $category = ProductGroup::active()->where('slug', $slug)->firstOrFail();

        $products = $category->products()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('client.store.category', compact('category', 'products'));
    }

    public function product(string $categorySlug, string $productSlug): View
    {
        $category = ProductGroup::active()->where('slug', $categorySlug)->firstOrFail();

        $product = Product::active()
            ->where('product_category_id', $category->id)
            ->where('slug', $productSlug)
            ->with(['optionGroups' => fn ($q) => $q->active()->with(['options' => fn ($q2) => $q2->active()])])
            ->firstOrFail();

        $related = Product::active()
            ->where('product_category_id', $category->id)
            ->where('id', '!=', $product->id)
            ->take(3)
            ->get();

        return view('client.store.product', compact('category', 'product', 'related'));
    }

    public function addProduct(Request $request, CartService $cart): RedirectResponse
    {
        $data = $request->validate([
            'product_id'         => ['required', 'exists:products,id'],
            'billing_cycle'      => ['required', 'in:monthly,quarterly,semi_annually,annually,custom'],
            'domain_mode'        => ['nullable', 'in:register,transfer,existing'],
            'domain_name'        => ['nullable', 'string', 'max:255'],
            'transfer_auth_code' => ['nullable', 'string', 'max:255'],
            'options'            => ['nullable', 'array'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        $result = $cart->addProduct(
            $product,
            $data['billing_cycle'],
            $data['domain_mode'] ?? null,
            $data['domain_name'] ?? null,
            $data['transfer_auth_code'] ?? null,
            $data['options'] ?? [],
        );

        if (! $result['success']) {
            return back()->withInput()->with('error', $result['message']);
        }

        return redirect()->route('client.cart')->with('success', $result['message']);
    }

    public function cart(CartService $cart): View
    {
        // Harga domain di keranjang disegarkan dulu, sama seperti keranjang publik.
        $cart->refreshPricing();

        return view('client.store.cart', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
        ]);
    }

    public function updateCycle(Request $request, CartService $cart): RedirectResponse
    {
        $data = $request->validate([
            'key'           => ['required', 'string'],
            'billing_cycle' => ['required', 'in:monthly,quarterly,semi_annually,annually,custom'],
        ]);

        $cart->updateProductCycle($data['key'], $data['billing_cycle']);

        return back()->with('success', 'Siklus tagihan diperbarui.');
    }

    public function updateYears(Request $request, CartService $cart): RedirectResponse
    {
        $data = $request->validate([
            'key'   => ['required', 'string'],
            'years' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $cart->updateDomainYears($data['key'], $data['years']);

        return back()->with('success', 'Lama registrasi domain diperbarui.');
    }

    public function remove(Request $request, CartService $cart): RedirectResponse
    {
        $cart->remove((string) $request->input('key'));

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear(CartService $cart): RedirectResponse
    {
        $cart->clear();

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
