@extends('layouts.admin')

@section('title', 'Server')

@section('content')

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Server</h1>
      <p class="small text-muted mb-0">Kelola server cPanel/WHM, DirectAdmin, Plesk, dan provider VM/VPS yang terhubung.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
    <form method="GET" class="d-flex gap-2">
      <select name="group" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:11rem">
        <option value="">Semua Grup</option>
        @foreach ($groups as $g)
          <option value="{{ $g->id }}" @selected((int) request('group') === $g->id)>{{ $g->name }}</option>
        @endforeach
      </select>
    </form>
    <a href="{{ route('admin.server-groups.index') }}" class="btn btn-outline-secondary btn-sm">Kelola Grup</a>
    <a href="{{ route('admin.servers.create') }}" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Server
    </a>
    </div>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama</th>
            <th class="py-3">Hostname</th>
            <th class="py-3">Grup</th>
            <th class="py-3">Panel</th>
            <th class="text-center py-3">Akun</th>
            <th class="py-3">Cek Terakhir</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($servers as $server)
            <tr>
              <td class="px-4 py-3 fw-medium text-dark">{{ $server->name }}</td>
              <td class="text-muted py-3">
                @if ($server->isCloud())
                  {{ $server->hostname ?: ($server->vpsDriver() === 'idcloudhost' ? 'Lokasi default' : 'API provider') }}
                @else
                  {{ $server->hostname . ':' . $server->port }}
                @endif
              </td>
              <td class="text-muted py-3">{{ $server->group?->name ?? '—' }}</td>
              <td class="text-muted text-capitalize py-3">
                @if ($server->isCloud())
                  VM / VPS · {{ $server->vpsLabel() }}
                @else
                  {{ $server->panelLabel() }}
                @endif
              </td>
              <td class="text-center text-muted py-3">{{ $server->hosting_accounts_count }}</td>
              <td class="text-muted py-3" style="font-size:12px">
                @if ($server->last_checked_at)
                  {{ $server->last_checked_at->diffForHumans() }}
                  <br>
                  <span class="{{ $server->last_check_status === 'ok' ? 'text-success' : 'text-danger' }}">
                    {{ $server->last_check_status === 'ok' ? 'Terhubung' : \Illuminate\Support\Str::limit($server->last_check_status, 40) }}
                  </span>
                @else
                  Belum pernah dicek
                @endif
              </td>
              <td class="py-3">
                <span class="badge {{ $server->is_active ? 'badge-soft-success' : 'badge-soft-secondary' }}">{{ $server->is_active ? 'Aktif' : 'Nonaktif' }}</span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  @if ($server->panel === 'cpanel')
                    <form method="POST" action="{{ route('admin.servers.login-whm', $server) }}" target="_blank">
                      @csrf
                      <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Login sekali klik ke WHM">
                        <i class="fa-solid fa-right-to-bracket" style="font-size:12px"></i>
                      </button>
                    </form>
                  @endif
                  @if ($server->panel === 'cpanel' && ! $server->isCloud())
                    <a href="{{ route('admin.servers.branding', $server) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Branding cPanel (logo)">
                      <i class="fa-solid fa-paintbrush" style="font-size:12px"></i>
                    </a>
                  @endif
                  <form method="POST" action="{{ route('admin.servers.test-connection', $server) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Tes Koneksi">
                      <i class="fa-solid fa-plug" style="font-size:12px"></i>
                    </button>
                  </form>
                  @if (! $server->isCloud() || $server->vpsDriver() === 'idcloudhost')
                    <a href="{{ route('admin.servers.diagnostics', $server) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Diagnosa">
                      <i class="fa-solid fa-stethoscope" style="font-size:12px"></i>
                    </a>
                  @endif
                  <a href="{{ route('admin.servers.edit', $server) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.servers.destroy', $server) }}" data-confirm="Hapus server ini?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted py-5">Belum ada server terhubung.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($servers->hasPages())
      <div class="px-4 py-3 border-top">{{ $servers->links('pagination.bootstrap') }}</div>
    @endif
  </div>

@endsection
