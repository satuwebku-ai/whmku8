@extends('layouts.admin')

@section('title', $group->exists ? 'Edit Server Group' : 'Tambah Server Group')

@section('content')

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">{{ $group->exists ? 'Edit Server Group' : 'Tambah Server Group' }}</h1>
  </div>

  <form method="POST" action="{{ $group->exists ? route('admin.server-groups.update', $group) : route('admin.server-groups.store') }}" class="card border rounded-4 p-4" style="max-width:640px">
    @csrf
    @if ($group->exists) @method('PUT') @endif

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Nama Group</label>
      <input type="text" name="name" value="{{ old('name', $group->name) }}" placeholder="ID-Jakarta" class="form-control form-control-sm" required>
      @error('name') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Lokasi</label>
        <input type="text" name="location" value="{{ old('location', $group->location) }}" placeholder="ID, SG, US, EU" class="form-control form-control-sm">
        @error('location') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Prioritas</label>
        <input type="number" name="priority" value="{{ old('priority', $group->priority ?? 100) }}" min="1" class="form-control form-control-sm" required>
        <p class="text-muted mb-0 mt-1" style="font-size:11px">Angka lebih kecil = diutamakan.</p>
        @error('priority') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Deskripsi (opsional)</label>
      <textarea name="description" rows="2" class="form-control form-control-sm">{{ old('description', $group->description) }}</textarea>
    </div>

    <label class="d-flex align-items-center gap-2 small text-dark mb-4">
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $group->is_active ?? true)) class="form-check-input" style="margin-top:0">
      Aktif
    </label>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
      <a href="{{ route('admin.server-groups.index') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

@endsection
