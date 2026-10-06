@extends('layouts.admin')

@section('title', $package->exists ? 'Edit Package' : 'Tambah Package')

@section('content')

  <div class="mb-4"><h1 class="h4 fw-bold text-dark mb-1">{{ $package->exists ? 'Edit Package' : 'Tambah Package' }}</h1></div>

  <form method="POST" action="{{ $package->exists ? route('admin.server-packages.update', $package) : route('admin.server-packages.store') }}" class="card border rounded-4 p-4" style="max-width:640px">
    @csrf
    @if ($package->exists) @method('PUT') @endif

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Server</label>
        <select name="server_id" class="form-select form-select-sm" required>
          <option value="">— Pilih server —</option>
          @foreach ($servers as $s)
            <option value="{{ $s->id }}" @selected((string) old('server_id', $package->server_id) === (string) $s->id)>{{ $s->name }}</option>
          @endforeach
        </select>
        @error('server_id') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Nama Plan di Panel</label>
        <input type="text" name="name" value="{{ old('name', $package->name) }}" placeholder="cloud_hosting_pro" class="form-control form-control-sm" required>
        @error('name') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="row g-3 mb-3">
      @foreach ([['disk_limit_mb', 'Disk (MB)'], ['bandwidth_limit_mb', 'Bandwidth (MB)'], ['cpu_limit', 'CPU (core)'], ['ram_limit_mb', 'RAM (MB)']] as [$f, $label])
        <div class="col-sm-3">
          <label class="form-label small fw-medium text-dark">{{ $label }}</label>
          <input type="number" min="0" name="{{ $f }}" value="{{ old($f, $package->{$f}) }}" class="form-control form-control-sm">
          @error($f) <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
        </div>
      @endforeach
    </div>
    <p class="text-muted mb-3" style="font-size:11px">Kosongkan bila tidak dicatat. 0 = unlimited (untuk disk dan bandwidth).</p>

    <label class="d-flex align-items-center gap-2 small text-dark mb-4">
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->is_active ?? true)) class="form-check-input" style="margin-top:0"> Aktif
    </label>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
      <a href="{{ route('admin.server-packages.index') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

@endsection
