@extends('layouts.admin')

@section('title', $group->exists ? 'Edit Grup Server' : 'Tambah Grup Server')

@section('content')
  <div class="mb-3">
    <h1 class="h4 fw-bold text-dark mb-1">{{ $group->exists ? 'Edit Grup Server' : 'Tambah Grup Server' }}</h1>
    <p class="small text-muted mb-0">Prioritas lebih kecil diletakkan lebih awal pada daftar.</p>
  </div>

  <form method="POST" action="{{ $group->exists ? route('admin.server-groups.update', $group) : route('admin.server-groups.store') }}" class="card border rounded-4 p-4" style="max-width:42rem">
    @csrf
    @if ($group->exists) @method('PUT') @endif
    <div class="mb-3">
      <label class="form-label small fw-medium">Nama grup</label>
      <input name="name" value="{{ old('name', $group->name) }}" class="form-control form-control-sm" required maxlength="120" placeholder="Contoh: Jakarta - Shared Hosting">
      @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="row g-3 mb-3">
      <div class="col-sm-8">
        <label class="form-label small fw-medium">Lokasi</label>
        <input name="location" value="{{ old('location', $group->location) }}" class="form-control form-control-sm" maxlength="120" placeholder="Jakarta, Indonesia">
        @error('location')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="col-sm-4">
        <label class="form-label small fw-medium">Prioritas</label>
        <input type="number" name="priority" min="0" max="100000" value="{{ old('priority', $group->priority ?? 0) }}" class="form-control form-control-sm" required>
        @error('priority')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small fw-medium">Status</label>
      <select name="status" class="form-select form-select-sm" required>
        <option value="active" @selected(old('status', $group->status ?? 'active') === 'active')>Aktif</option>
        <option value="inactive" @selected(old('status', $group->status) === 'inactive')>Nonaktif</option>
      </select>
      @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="mb-4">
      <label class="form-label small fw-medium">Deskripsi</label>
      <textarea name="description" rows="3" class="form-control form-control-sm">{{ old('description', $group->description) }}</textarea>
      @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-primary btn-sm" type="submit">Simpan Grup</button>
      <a href="{{ route('admin.server-groups.index') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>
@endsection
