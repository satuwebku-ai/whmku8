@extends('layouts.admin')

@section('title', 'Server Group')

@section('content')

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Server Group</h1>
      <p class="small text-muted mb-0">Kelompokkan server berdasarkan lokasi/peruntukan, mis. Jakarta, Singapore, Reseller.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.servers.index') }}" class="btn btn-outline-secondary btn-sm">Lihat Server</a>
      <a href="{{ route('admin.server-groups.create') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Grup</a>
    </div>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama</th>
            <th class="py-3">Slug</th>
            <th class="text-center py-3">Server</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($groups as $group)
            <tr>
              <td class="px-4 py-3">
                <div class="fw-medium text-dark">{{ $group->name }}</div>
                @if ($group->description) <div class="text-muted" style="font-size:12px">{{ $group->description }}</div> @endif
              </td>
              <td class="text-muted py-3"><code>{{ $group->slug }}</code></td>
              <td class="text-center text-muted py-3">
                <a href="{{ route('admin.servers.index', ['group' => $group->id]) }}" class="text-decoration-none">{{ $group->servers_count }}</a>
              </td>
              <td class="py-3"><span class="badge {{ $group->is_active ? 'badge-soft-success' : 'badge-soft-secondary' }}">{{ $group->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <a href="{{ route('admin.server-groups.edit', $group) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit"><i class="fa-regular fa-pen-to-square" style="font-size:12px"></i></a>
                  <form method="POST" action="{{ route('admin.server-groups.destroy', $group) }}" data-confirm="Hapus grup {{ $group->name }}?">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus"><i class="fa-regular fa-trash-can" style="font-size:12px"></i></button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-5">Belum ada server group. Grup juga otomatis terbuat saat Anda mengetik nama grup baru di form Server.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">{{ $groups->links() }}</div>

@endsection
