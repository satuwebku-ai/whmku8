<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\ServerPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServerPackageController extends Controller
{
    public function index(Request $request): View
    {
        $packages = ServerPackage::with('server')
            ->when($request->integer('server_id'), fn ($q, $id) => $q->where('server_id', $id))
            ->orderBy('server_id')->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.server-packages.index', ['packages' => $packages, 'servers' => $this->hostingServers()]);
    }

    public function sync(Request $request, \App\Services\Hosting\ServerPackageSyncService $sync): RedirectResponse
    {
        $data = $request->validate(['server_id' => ['required', 'integer', 'exists:servers,id']]);
        $server = Server::findOrFail($data['server_id']);

        if ($server->isCloud()) {
            return back()->with('error', 'Server VM/VPS tidak memakai package panel.');
        }

        $result = $sync->sync($server);

        if (! $result['success']) {
            return back()->with('error', 'Sinkronisasi gagal: ' . $result['message']);
        }

        $message = "Sinkronisasi {$server->name} selesai: {$result['created']} package baru, {$result['updated']} diperbarui.";
        if ($result['missing']) {
            $message .= ' Ada di whmku tapi tidak ditemukan di server: ' . implode(', ', $result['missing']) . '.';
        }

        return back()->with('success', $message);
    }

    public function create(Request $request): View
    {
        return view('admin.server-packages.form', [
            'package' => new ServerPackage(['server_id' => $request->integer('server_id') ?: null, 'is_active' => true]),
            'servers' => $this->hostingServers(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        ServerPackage::create($this->validated($request));

        return redirect()->route('admin.server-packages.index')->with('success', 'Package berhasil ditambahkan.');
    }

    public function edit(ServerPackage $server_package): View
    {
        return view('admin.server-packages.form', ['package' => $server_package, 'servers' => $this->hostingServers()]);
    }

    public function update(Request $request, ServerPackage $server_package): RedirectResponse
    {
        $server_package->update($this->validated($request, $server_package));

        // Nama plan yang dipakai produk ikut selaras.
        $server_package->products()->update(['panel_package' => $server_package->name]);

        return redirect()->route('admin.server-packages.index')->with('success', 'Package berhasil diperbarui.');
    }

    public function destroy(ServerPackage $server_package): RedirectResponse
    {
        if ($server_package->products()->exists()) {
            return back()->with('error', 'Package tidak bisa dihapus karena masih dipakai produk.');
        }

        $server_package->delete();

        return redirect()->route('admin.server-packages.index')->with('success', 'Package berhasil dihapus.');
    }

    /** Package hanya relevan untuk server hosting panel (bukan VM/VPS). */
    private function hostingServers()
    {
        return Server::orderBy('name')->get()->reject(fn (Server $s) => $s->isCloud())->values();
    }

    private function validated(Request $request, ?ServerPackage $current = null): array
    {
        $serverId = $request->integer('server_id');
        $data = $request->validate([
            'server_id' => ['required', 'integer', 'exists:servers,id'],
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('server_packages', 'name')->where('server_id', $serverId)->ignore($current?->id),
            ],
            'disk_limit_mb' => ['nullable', 'integer', 'min:0'],
            'bandwidth_limit_mb' => ['nullable', 'integer', 'min:0'],
            'cpu_limit' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'ram_limit_mb' => ['nullable', 'integer', 'min:0'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
