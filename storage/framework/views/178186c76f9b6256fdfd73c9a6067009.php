<div class="mb-3">
  <label class="form-label small fw-medium text-dark">Tautan Menuju</label>
  <div class="row g-2">
    <?php $__currentLoopData = ['route' => 'Halaman Bawaan', 'page' => 'Halaman Saya', 'url' => 'Tautan Bebas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php $isActiveType = old('type', $menu->type) === $key; ?>
      <div class="col-md-4">
        <label class="d-flex align-items-center justify-content-center rounded-3 border px-2 py-2 text-center small fw-medium w-100" style="cursor:pointer;<?php echo e($isActiveType ? 'border-color:#4f46e5!important;background:rgba(79,70,229,.06);color:#4338ca' : ''); ?>">
          <input type="radio" name="type" value="<?php echo e($key); ?>" <?php if($isActiveType): echo 'checked'; endif; ?> class="d-none" data-type-radio>
          <?php echo e($label); ?>

        </label>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</div>

<div data-type-field="route" class="mb-3 <?php echo e(old('type', $menu->type) === 'route' ? '' : 'd-none'); ?>">
  <label class="form-label small fw-medium text-dark">Pilih Halaman Bawaan</label>
  <select name="route_name" class="form-select form-select-sm">
    <option value="">— Pilih —</option>
    <?php $__currentLoopData = \App\Models\NavMenu::BUILTIN_ROUTES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($key); ?>" <?php if(old('route_name', $menu->route_name) === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </select>
  <?php $__errorArgs = ['route_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div data-type-field="page" class="mb-3 <?php echo e(old('type', $menu->type) === 'page' ? '' : 'd-none'); ?>">
  <label class="form-label small fw-medium text-dark">Pilih Halaman CMS</label>
  <select name="page_id" class="form-select form-select-sm">
    <option value="">— Pilih —</option>
    <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <option value="<?php echo e($page->id); ?>" <?php if((int) old('page_id', $menu->page_id) === $page->id): echo 'selected'; endif; ?>><?php echo e($page->title); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <option value="" disabled>Belum ada halaman yang diterbitkan</option>
    <?php endif; ?>
  </select>
  <?php $__errorArgs = ['page_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  <p class="text-muted mt-1 mb-0" style="font-size:11px">Hanya halaman yang sudah terbit yang tersedia.</p>
</div>

<div data-type-field="url" class="mb-3 <?php echo e(old('type', $menu->type) === 'url' ? '' : 'd-none'); ?>">
  <label class="form-label small fw-medium text-dark">Alamat Tautan</label>
  <input type="text" name="url" value="<?php echo e(old('url', $menu->url)); ?>" class="form-control form-control-sm" placeholder="https://wa.me/6281234567890">
  <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<label class="d-flex align-items-center gap-2 small text-dark mb-3">
  <input type="checkbox" name="open_in_new_tab" value="1" <?php if(old('open_in_new_tab', $menu->open_in_new_tab)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
  Buka di tab baru
</label>

<script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
(function () {
  const radios = document.querySelectorAll('[data-type-radio]');
  const fields = document.querySelectorAll('[data-type-field]');

  function sync() {
    const active = document.querySelector('[data-type-radio]:checked')?.value;
    fields.forEach(el => el.classList.toggle('d-none', el.dataset.typeField !== active));
    radios.forEach(r => {
      const label = r.closest('label');
      if (r.checked) {
        label.style.borderColor = '#4f46e5';
        label.style.background = 'rgba(79,70,229,.06)';
        label.style.color = '#4338ca';
      } else {
        label.style.borderColor = '';
        label.style.background = '';
        label.style.color = '';
      }
    });
  }

  radios.forEach(r => r.addEventListener('change', sync));
  sync();
})();
</script>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/nav-menus/_destination-fields.blade.php ENDPATH**/ ?>