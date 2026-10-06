@extends('layouts.admin')

@section('title', $group->exists ? 'Edit Server Group' : 'Tambah Server Group')

@section('content')

  <h1 class="h4 fw-bold text-dark mb-4">{{ $group->exists ? 'Edit Server Group' : 'Tambah Server Group' }}</h1>

  <form method="POST" action="{{ $group->exists ? route('admin.server-groups.update', $group) : route('admin.server-groups.store') }}" class="card border rounded-4 p-4" style="max-width:36rem">
    @csrf
    @if ($group->exists) @method('PUT') @endif

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Nama Grup</label>
      <input type="text" name="name" id="nameInput" value="{{ old('name', $group->name) }}" class="form-control form-control-sm" placeholder="Jakarta" required>
      @error('name') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Slug</label>
      <input type="text" name="slug" id="slugInput" value="{{ old('slug', $group->slug) }}" class="form-control form-control-sm" placeholder="otomatis dari nama">
      @error('slug') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Deskripsi <span class="text-muted fw-normal">(opsional)</span></label>
      <textarea name="description" rows="2" maxlength="500" class="form-control form-control-sm">{{ old('description', $group->description) }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Urutan Tampil</label>
      <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $group->sort_order ?? 0) }}" class="form-control form-control-sm">
    </div>

    <label class="d-flex align-items-center gap-2 small text-dark mb-3">
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $group->is_active ?? true)) class="form-check-input" style="margin-top:0">
      Aktif
    </label>

    <div class="d-flex align-items-center gap-2 pt-2 border-top">
      <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="{{ route('admin.server-groups.index') }}" class="btn btn-outline-secondary btn-sm mt-2">Batal</a>
    </div>
  </form>

  <script @nonce>
    (function () {
      const name = document.getElementById('nameInput');
      const slug = document.getElementById('slugInput');
      const slugify = (s) => s.toLowerCase().trim().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
      let touched = slug.value.length > 0;
      slug.addEventListener('input', () => { touched = true; });
      name.addEventListener('input', () => { if (!touched) slug.value = slugify(name.value); });
    })();
  </script>

@endsection
