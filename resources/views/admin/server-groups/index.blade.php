@extends('layouts.admin')

@section('title', 'Grup Server')

@section('content')
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Grup Server</h1>
      <p class="small text-muted mb-0">Kelompokkan server berdasarkan lokasi, prioritas, dan status.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.servers.index') }}" class="btn btn-outline-secondary">Kembali ke Server</a>
      <a href="{{ route('admin.server-groups.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Grup</a>
    </div>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama</th>
            <th class="py-3">Lokasi</th>
            <th class="py-3">Prioritas</th>
            <th class="py-3">Server</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($groups as $group)
            <tr>
              <td class="px-4 py-3">
                <div class="fw-medium">{{ $group->name }}</div>
                @if ($group->description)<div class="small text-muted">{{ $group->description }}</div>@endif
              </td>
              <td>{{ $group->location ?: '—' }}</td>
              <td>{{ $group->priority }}</td>
              <td>{{ $group->servers_count }}</td>
              <td><span class="badge {{ $group->status === 'active' ? 'badge-soft-success' : 'badge-soft-secondary' }}">{{ $group->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span></td>
              <td class="text-end px-4">
                <div class="d-inline-flex gap-2">
                  <a href="{{ route('admin.server-groups.edit', $group) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                  <form method="POST" action="{{ route('admin.server-groups.destroy', $group) }}" data-confirm="Hapus grup server ini?" data-confirm-title="Hapus Grup" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit" @disabled($group->servers_count > 0)>Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada grup server.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($groups->hasPages())<div class="px-4 py-3 border-top">{{ $groups->links('pagination.bootstrap') }}</div>@endif
  </div>
@endsection
