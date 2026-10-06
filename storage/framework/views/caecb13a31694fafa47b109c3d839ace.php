<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['url']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['url']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $mailLogo = \App\Models\Setting::get('site_logo');
    $mailShowLogo = \App\Models\Setting::get('email_show_logo', '1') === '1';
    $mailLogoH = max(16, min(200, (int) \App\Models\Setting::get('email_logo_height', 40)));
    $mailSite = \App\Models\Setting::get('site_name', config('app.name'));
?>
<tr>
<td class="header">
<a href="<?php echo new \Illuminate\Support\EncodedHtmlString($url); ?>" style="display: inline-block;">
<?php if($mailLogo && $mailShowLogo): ?>
<img src="<?php echo new \Illuminate\Support\EncodedHtmlString(route('branding.file', $mailLogo)); ?>" alt="<?php echo new \Illuminate\Support\EncodedHtmlString($mailSite); ?>" height="<?php echo new \Illuminate\Support\EncodedHtmlString($mailLogoH); ?>" style="height: <?php echo new \Illuminate\Support\EncodedHtmlString($mailLogoH); ?>px; width: auto; max-width: 100%; border: 0; display: block; margin: 0 auto;">
<?php else: ?>
<?php echo new \Illuminate\Support\EncodedHtmlString($mailSite); ?>

<?php endif; ?>
</a>
</td>
</tr>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/vendor/mail/html/header.blade.php ENDPATH**/ ?>