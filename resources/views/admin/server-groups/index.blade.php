@extends('layouts.admin')

@section('title', 'Server Group')

@section('content')

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Server Group</h1>
      <p class="small text-muted mb-0">Kelompok server per lokasi. Order baru dialokasikan ke server di group yang terpasang pada produk, berdasarkan prioritas dan kapasitas.</p>
    </div>
    <a href="{{ route('admin.server-groups.create') }}" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Server Group
    </a>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama</th>
            <th class="py-3">Lokasi</th>
            <th class="text-center py-3">Prioritas</th>
            <th class="text-center py-3">Server</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($groups as $group)
            <tr>
              <td class="px-4 py-3 fw-medium text-dark">{{ $group->name }}</td>
              <td class="text-muted py-3">{{ $group->location ?: '—' }}</td>
              <td class="text-center py-3">{{ $group->priority }}</td>
              <td class="text-center py-3">{{ $group->servers_count }}</td>
              <td class="py-3">
                <span class="badge {{ $group->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $group->is_active ? 'Aktif' : 'Nonaktif' }}</span>
              </td>
              <td class="text-end px-4 py-3">
                <a href="{{ route('admin.server-groups.edit', $group) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                <form method="POST" action="{{ route('admin.server-groups.destroy', $group) }}" class="d-inline" data-confirm="Hapus server group ini?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada server group.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">{{ $groups->links() }}</div>

@endsection
