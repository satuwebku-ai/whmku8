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
    public function index(Server $server): View
    {
        $this->ensureHostingServer($server);
        $packages = $server->serverPackages()
            ->withCount(['products', 'hostingAccounts'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.server-packages.index', compact('server', 'packages'));
    }

    public function create(Server $server): View
    {
        $this->ensureHostingServer($server);

        return view('admin.server-packages.form', [
            'server' => $server,
            'package' => new ServerPackage(),
        ]);
    }

    public function store(Request $request, Server $server): RedirectResponse
    {
        $this->ensureHostingServer($server);
        $server->serverPackages()->create($this->validated($request, $server));

        return redirect()->route('admin.servers.packages.index', $server)->with('success', 'Paket server berhasil ditambahkan.');
    }

    public function edit(Server $server, ServerPackage $package): View
    {
        $this->ensureHostingServer($server);
        $this->ensurePackageBelongsToServer($server, $package);

        return view('admin.server-packages.form', compact('server', 'package'));
    }

    public function update(Request $request, Server $server, ServerPackage $package): RedirectResponse
    {
        $this->ensureHostingServer($server);
        $this->ensurePackageBelongsToServer($server, $package);
        $package->update($this->validated($request, $server, $package));

        return redirect()->route('admin.servers.packages.index', $server)->with('success', 'Paket server berhasil diperbarui.');
    }

    public function destroy(Server $server, ServerPackage $package): RedirectResponse
    {
        $this->ensureHostingServer($server);
        $this->ensurePackageBelongsToServer($server, $package);

        if ($package->products()->exists() || $package->hostingAccounts()->exists()) {
            return back()->with('error', 'Paket masih ditautkan ke produk atau layanan. Nonaktifkan paket agar tidak dipilih untuk order baru.');
        }

        $package->delete();

        return redirect()->route('admin.servers.packages.index', $server)->with('success', 'Paket server berhasil dihapus.');
    }

    private function validated(Request $request, Server $server, ?ServerPackage $package = null): array
    {
        $uniqueName = Rule::unique('server_packages', 'name')
            ->where(fn ($query) => $query->where('server_id', $server->id));

        if ($package) {
            $uniqueName->ignore($package->id);
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:100', $uniqueName],
            'disk_limit' => ['nullable', 'integer', 'min:1'],
            'bandwidth_limit' => ['nullable', 'integer', 'min:1'],
            'cpu_limit' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'ram_limit' => ['nullable', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }

    private function ensureHostingServer(Server $server): void
    {
        abort_if($server->isCloud(), 404, 'Inventaris paket ini hanya untuk server hosting.');
    }

    private function ensurePackageBelongsToServer(Server $server, ServerPackage $package): void
    {
        abort_unless((int) $package->server_id === (int) $server->id, 404);
    }
}
