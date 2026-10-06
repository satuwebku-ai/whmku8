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
        $groups = ServerGroup::withCount('servers')->orderBy('priority')->orderBy('name')->paginate(15);

        return view('admin.server-groups.index', compact('groups'));
    }

    public function create(): View
    {
        return view('admin.server-groups.form', ['group' => new ServerGroup(['priority' => 100, 'is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        ServerGroup::create($data);

        return redirect()->route('admin.server-groups.index')->with('success', 'Server group berhasil ditambahkan.');
    }

    public function edit(ServerGroup $server_group): View
    {
        return view('admin.server-groups.form', ['group' => $server_group]);
    }

    public function update(Request $request, ServerGroup $server_group): RedirectResponse
    {
        $server_group->update($this->validated($request));

        return redirect()->route('admin.server-groups.index')->with('success', 'Server group berhasil diperbarui.');
    }

    public function destroy(ServerGroup $server_group): RedirectResponse
    {
        if ($server_group->servers()->exists()) {
            return back()->with('error', 'Server group tidak bisa dihapus karena masih berisi server. Pindahkan server-nya dulu.');
        }

        $server_group->delete();

        return redirect()->route('admin.server-groups.index')->with('success', 'Server group berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'location'    => ['nullable', 'string', 'max:100'],
            'priority'    => ['required', 'integer', 'min:1', 'max:9999'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'group';
        $slug = $base;
        $i = 2;
        while (ServerGroup::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
