@extends('layouts.admin')

@section('title', $group->exists ? 'Edit Grup Server' : 'Tambah Grup Server')

@section('content')

  @php $selectStyle = 'padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem'; @endphp

  <div class="mb-3">
    <h1 class="h4 fw-bold text-dark mb-1">{{ $group->exists ? 'Edit Grup Server' : 'Tambah Grup Server' }}</h1>
    <p class="small text-muted mb-0">Masukkan server ke grup lewat halaman Edit Server, lalu pilih grup ini di halaman Produk.</p>
  </div>

  <form method="POST" action="{{ $group->exists ? route('admin.server-groups.update', $group) : route('admin.server-groups.store') }}" class="card border rounded-4 p-4" style="max-width:42rem" autocomplete="off">
    @csrf
    @if ($group->exists) @method('PUT') @endif

    <div class="row g-3 mb-3">
      <div class="col-sm-7">
        <label class="form-label small fw-medium text-dark">Nama Grup</label>
        <input type="text" name="name" value="{{ old('name', $group->name) }}" placeholder="Shared Hosting" class="form-control form-control-sm" required>
        @error('name') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
      </div>
      <div class="col-sm-5">
        <label class="form-label small fw-medium text-dark">Slug (opsional)</label>
        <input type="text" name="slug" value="{{ old('slug', $group->slug) }}" placeholder="otomatis dari nama" class="form-control form-control-sm">
        @error('slug') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Mode Pemilihan Server</label>
      <select name="selection_mode" class="form-select" style="{{ $selectStyle }}">
        @foreach (\App\Models\ServerGroup::MODES as $key => $label)
          <option value="{{ $key }}" @selected(old('selection_mode', $group->selection_mode) === $key)>{{ $label }}</option>
        @endforeach
      </select>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">
        Server yang penuh (Kapasitas Maks. Akun), nonaktif, atau sedang maintenance selalu dilewati, apa pun modenya.
      </p>
      @error('selection_mode') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Deskripsi (opsional)</label>
      <textarea name="description" rows="2" class="form-control form-control-sm">{{ old('description', $group->description) }}</textarea>
      @error('description') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
    </div>

    <label class="d-flex align-items-center gap-2 small text-dark mb-3">
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $group->is_active ?? true)) class="form-check-input" style="margin-top:0">
      Aktif
    </label>

    <div class="d-flex align-items-center gap-2 pt-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="{{ route('admin.server-groups.index') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

@endsection
