@extends('layouts.admin')

@section('title', 'Server Package')

@section('content')

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Server Package</h1>
      <p class="small text-muted mb-0">Plan resource per server (disk, bandwidth, CPU, RAM). Nama harus sama persis dengan plan di WHM/panel.</p>
    </div>
    <a href="{{ route('admin.server-packages.create', request('server_id') ? ['server_id' => request('server_id')] : []) }}" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Package
    </a>
  </div>

  @if (request('server_id'))
    <form method="POST" action="{{ route('admin.server-packages.sync') }}" class="mb-3">
      @csrf
      <input type="hidden" name="server_id" value="{{ (int) request('server_id') }}">
      <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-rotate" style="font-size:11px"></i> Sinkronkan dari WHM</button>
      <span class="text-muted ms-2" style="font-size:11px">Menambah/memperbarui disk &amp; bandwidth dari server terpilih. Tidak menghapus package lokal.</span>
    </form>
  @endif

  <form method="GET" class="mb-3 d-flex gap-2">
    <select name="server_id" class="form-select form-select-sm" style="max-width:280px">
      <option value="">Semua server</option>
      @foreach ($servers as $s)
        <option value="{{ $s->id }}" @selected((int) request('server_id') === $s->id)>{{ $s->name }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
  </form>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama Plan</th>
            <th class="py-3">Server</th>
            <th class="py-3">Disk</th>
            <th class="py-3">Bandwidth</th>
            <th class="py-3">CPU</th>
            <th class="py-3">RAM</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($packages as $p)
            <tr>
              <td class="px-4 py-3 fw-medium text-dark">{{ $p->name }}</td>
              <td class="text-muted py-3">{{ $p->server?->name }}</td>
              <td class="py-3">{{ $p->disk_limit_mb === null ? '—' : ($p->disk_limit_mb == 0 ? 'Unlimited' : number_format($p->disk_limit_mb) . ' MB') }}</td>
              <td class="py-3">{{ $p->bandwidth_limit_mb === null ? '—' : ($p->bandwidth_limit_mb == 0 ? 'Unlimited' : number_format($p->bandwidth_limit_mb) . ' MB') }}</td>
              <td class="py-3">{{ $p->cpu_limit ?? '—' }}</td>
              <td class="py-3">{{ $p->ram_limit_mb === null ? '—' : number_format($p->ram_limit_mb) . ' MB' }}</td>
              <td class="py-3"><span class="badge {{ $p->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $p->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
              <td class="text-end px-4 py-3">
                <a href="{{ route('admin.server-packages.edit', $p) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                <form method="POST" action="{{ route('admin.server-packages.destroy', $p) }}" class="d-inline" data-confirm="Hapus package ini?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center text-muted py-5">Belum ada package.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">{{ $packages->links() }}</div>

@endsection
