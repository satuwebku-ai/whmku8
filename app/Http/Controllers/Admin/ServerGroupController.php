<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServerGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServerGroupController extends Controller
{
    public function index(): View
    {
        $groups = ServerGroup::withCount('servers')
            ->orderBy('priority')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.server-groups.index', compact('groups'));
    }

    public function create(): View
    {
        return view('admin.server-groups.form', ['group' => new ServerGroup()]);
    }

    public function store(Request $request): RedirectResponse
    {
        ServerGroup::create($this->validated($request));

        return redirect()->route('admin.server-groups.index')->with('success', 'Grup server berhasil dibuat.');
    }

    public function edit(ServerGroup $serverGroup): View
    {
        return view('admin.server-groups.form', ['group' => $serverGroup]);
    }

    public function update(Request $request, ServerGroup $serverGroup): RedirectResponse
    {
        $serverGroup->update($this->validated($request, $serverGroup));

        return redirect()->route('admin.server-groups.index')->with('success', 'Grup server berhasil diperbarui.');
    }

    public function destroy(ServerGroup $serverGroup): RedirectResponse
    {
        if ($serverGroup->servers()->exists()) {
            return back()->with('error', 'Grup ini masih dipakai server. Pindahkan server ke grup lain sebelum menghapusnya.');
        }

        $serverGroup->delete();

        return redirect()->route('admin.server-groups.index')->with('success', 'Grup server berhasil dihapus.');
    }

    private function validated(Request $request, ?ServerGroup $group = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'location' => ['nullable', 'string', 'max:120'],
            'priority' => ['required', 'integer', 'min:0', 'max:100000'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data['slug'] = ServerGroup::uniqueSlug($data['name'], $group?->id);

        return $data;
    }
}
