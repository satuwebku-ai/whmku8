@extends('layouts.admin')

@section('title', $package->exists ? 'Edit Paket Server' : 'Tambah Paket Server')

@section('content')
  <div class="mb-3">
    <h1 class="h4 fw-bold text-dark mb-1">{{ $package->exists ? 'Edit Paket Server' : 'Tambah Paket Server' }}</h1>
    <p class="small text-muted mb-0">{{ $server->name }} · Paket ini hanya bisa ditautkan ke produk pada server yang sama.</p>
  </div>

  <form method="POST" action="{{ $package->exists ? route('admin.servers.packages.update', [$server, $package]) : route('admin.servers.packages.store', $server) }}" class="card border rounded-4 p-4" style="max-width:46rem">
    @csrf
    @if ($package->exists) @method('PUT') @endif
    <div class="mb-3">
      <label class="form-label small fw-medium">Nama paket di panel</label>
      <input name="name" value="{{ old('name', $package->name) }}" class="form-control form-control-sm" required maxlength="100" placeholder="cloud_hosting_pro">
      @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="row g-3 mb-3">
      <div class="col-6 col-lg-3">
        <label class="form-label small fw-medium">Disk (GB)</label>
        <input type="number" name="disk_limit" min="1" value="{{ old('disk_limit', $package->disk_limit) }}" class="form-control form-control-sm" placeholder="Tanpa batas">
        @error('disk_limit')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="col-6 col-lg-3">
        <label class="form-label small fw-medium">Bandwidth (GB)</label>
        <input type="number" name="bandwidth_limit" min="1" value="{{ old('bandwidth_limit', $package->bandwidth_limit) }}" class="form-control form-control-sm" placeholder="Tanpa batas">
        @error('bandwidth_limit')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="col-6 col-lg-3">
        <label class="form-label small fw-medium">CPU (core)</label>
        <input type="number" name="cpu_limit" min="1" max="65535" value="{{ old('cpu_limit', $package->cpu_limit) }}" class="form-control form-control-sm">
        @error('cpu_limit')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="col-6 col-lg-3">
        <label class="form-label small fw-medium">RAM (MB)</label>
        <input type="number" name="ram_limit" min="1" value="{{ old('ram_limit', $package->ram_limit) }}" class="form-control form-control-sm">
        @error('ram_limit')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
    </div>
    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium">Harga acuan (Rp)</label>
        <input type="number" name="price" min="0" step="0.01" value="{{ old('price', $package->price ?? 0) }}" class="form-control form-control-sm">
        <div class="text-muted small mt-1">Harga penjualan tetap diatur per siklus pada produk.</div>
        @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium">Status</label>
        <select name="status" class="form-select form-select-sm" required>
          <option value="active" @selected(old('status', $package->status ?? 'active') === 'active')>Aktif</option>
          <option value="inactive" @selected(old('status', $package->status) === 'inactive')>Nonaktif</option>
        </select>
        @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-primary btn-sm" type="submit">Simpan Paket</button>
      <a href="{{ route('admin.servers.packages.index', $server) }}" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>
@endsection
