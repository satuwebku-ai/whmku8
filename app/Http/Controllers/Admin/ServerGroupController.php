<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServerGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServerGroupController extends Controller
{
    public function index(): View
    {
        $groups = ServerGroup::withCount(['servers', 'products'])
            ->with(['servers' => fn ($q) => $q->withCount(['hostingAccounts as active_accounts_count' => fn ($a) => $a->whereNotIn('status', \App\Models\Server::INACTIVE_ACCOUNT_STATUSES)])])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.server-groups.index', compact('groups'));
    }

    public function create(): View
    {
        return view('admin.server-groups.form', ['group' => new ServerGroup(['selection_mode' => 'least_accounts', 'is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        ServerGroup::create($data);

        return redirect()->route('admin.server-groups.index')->with('success', 'Grup server berhasil dibuat.');
    }

    public function edit(ServerGroup $serverGroup): View
    {
        return view('admin.server-groups.form', ['group' => $serverGroup]);
    }

    public function update(Request $request, ServerGroup $serverGroup): RedirectResponse
    {
        $data = $this->validated($request, $serverGroup->id);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['name'], $serverGroup->id);
        $data['is_active'] = $request->boolean('is_active');

        $serverGroup->update($data);

        return redirect()->route('admin.server-groups.index')->with('success', 'Grup server berhasil diperbarui.');
    }

    public function destroy(ServerGroup $serverGroup): RedirectResponse
    {
        if ($serverGroup->servers()->exists()) {
            return back()->with('error', 'Grup tidak bisa dihapus karena masih berisi server. Pindahkan atau keluarkan server dari grup ini dulu.');
        }

        if ($serverGroup->products()->exists()) {
            return back()->with('error', 'Grup tidak bisa dihapus karena masih dipakai produk. Ganti Grup Server di produk terkait dulu.');
        }

        $serverGroup->delete();

        return redirect()->route('admin.server-groups.index')->with('success', 'Grup server berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'slug'           => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('server_groups', 'slug')->ignore($ignoreId)],
            'description'    => ['nullable', 'string', 'max:1000'],
            'selection_mode' => ['required', Rule::in(array_keys(ServerGroup::MODES))],
            'is_active'      => ['nullable', 'boolean'],
        ], [
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
        ]);
    }

    /** Slug dari nama kalau kosong; ditambah angka kalau sudah dipakai grup lain. */
    private function uniqueSlug(?string $slug, string $name, ?int $ignoreId = null): string
    {
        $base = filled($slug) ? $slug : (Str::slug($name) ?: 'grup');
        $candidate = $base;
        $i = 2;

        while (ServerGroup::where('slug', $candidate)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $candidate = $base . '-' . $i++;
        }

        return $candidate;
    }
}
