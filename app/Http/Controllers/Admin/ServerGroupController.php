<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ServerGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServerGroupController extends Controller
{
    public function index(): View
    {
        $groups = ServerGroup::withCount('servers')->orderBy('sort_order')->orderBy('name')->paginate(15);

        return view('admin.server-groups.index', compact('groups'));
    }

    public function create(): View
    {
        return view('admin.server-groups.form', ['group' => new ServerGroup()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active', true);

        $group = ServerGroup::create($data);
        $this->audit('Server group dibuat', $group->name, 'info');

        return redirect()->route('admin.server-groups.index')->with('success', 'Server group berhasil dibuat.');
    }

    public function edit(ServerGroup $serverGroup): View
    {
        return view('admin.server-groups.form', ['group' => $serverGroup]);
    }

    public function update(Request $request, ServerGroup $serverGroup): RedirectResponse
    {
        $data = $this->validated($request, $serverGroup->id);
        $data['is_active'] = $request->boolean('is_active');

        $serverGroup->update($data);
        $this->audit('Server group diubah', $serverGroup->name, 'info');

        return redirect()->route('admin.server-groups.index')->with('success', 'Server group berhasil diperbarui.');
    }

    public function destroy(ServerGroup $serverGroup): RedirectResponse
    {
        if ($serverGroup->servers()->exists()) {
            return back()->with('error', 'Grup tidak bisa dihapus karena masih berisi server. Pindahkan servernya ke grup lain dulu.');
        }

        $name = $serverGroup->name;
        $serverGroup->delete();
        $this->audit('Server group dihapus', $name, 'warning');

        return redirect()->route('admin.server-groups.index')->with('success', 'Server group berhasil dihapus.');
    }

    private function audit(string $title, string $detail, string $level): void
    {
        $who = auth('admin')->user()->name ?? 'admin';

        ActivityLog::record('service', $title, "{$detail}. Oleh {$who}.", null, $level);
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', 'unique:server_groups,slug' . ($ignoreId ? ",{$ignoreId}" : '')],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ]);
    }
}
