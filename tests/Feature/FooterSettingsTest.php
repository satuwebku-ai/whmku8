<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Setting;
use Database\Seeders\AnnouncementSeeder;
use Database\Seeders\CmsPageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterSettingsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create([
            'username' => 'cms', 'name' => 'CMS', 'email' => 'cms@contoh.test',
            'password' => bcrypt('Rahasia-123'), 'role' => 'superadmin', 'is_active' => true,
        ]);
    }

    public function test_default_footer_shows_all_blocks(): void
    {
        $this->seed(CmsPageSeeder::class);

        $this->get(route('home'))->assertOk()
            ->assertSee('Semua paket')->assertSee('Pusat Bantuan')->assertSee('Tentang Kami');
    }

    public function test_footer_settings_change_order_visibility_size_and_layout(): void
    {
        $this->seed(CmsPageSeeder::class);
        $this->actingAs($this->admin(), 'admin');

        $this->get(route('admin.settings.footer'))->assertOk()->assertSee('Susunan Menu Footer');

        $this->post(route('admin.settings.footer.update'), [
            'footer_size' => 'large', 'footer_layout' => 'columns',
            'footer_order' => 'help,products,brand',
            'footer_show_help' => '1', 'footer_show_products' => '1', 'footer_show_brand' => '1',
        ])->assertRedirect();

        $this->assertSame('large', Setting::get('footer_size'));
        $this->assertSame(['help', 'products', 'brand'], array_slice(\App\Support\FooterConfig::order(), 0, 3));

        $html = $this->get(route('home'))->assertOk()->getContent();
        $this->assertLessThan(strpos($html, 'Semua paket'), strpos($html, 'Pengumuman'), 'Blok Bantuan harus di depan Produk.');
        $this->assertStringContainsString('padding-top:4.5rem', $html);
        $this->assertStringNotContainsString('Tentang Kami</a></li>', $html, 'Blok Informasi disembunyikan.');
    }

    public function test_inline_layout_renders_links_in_one_row(): void
    {
        $this->actingAs($this->admin(), 'admin');
        $this->post(route('admin.settings.footer.update'), [
            'footer_size' => 'small', 'footer_layout' => 'inline', 'footer_order' => '',
            'footer_show_help' => '1',
        ])->assertRedirect();

        $this->get(route('home'))->assertOk()->assertSee('Blog')->assertDontSee('Semua paket');
    }

    public function test_announcement_seeder_is_idempotent(): void
    {
        $this->seed(AnnouncementSeeder::class);
        $this->seed(AnnouncementSeeder::class);

        $this->assertSame(4, \App\Models\Announcement::count());
        $this->get(route('announcements.index'))->assertOk()->assertSee('Selamat datang');
    }

    public function test_footer_follows_the_site_logo_and_branding_mode(): void
    {
        Setting::put('site_logo', 'logo-utama.png');
        Setting::put('site_icon', 'ikon.png');

        Setting::put('branding_display', 'logo_and_text');
        $footer = strstr($this->get(route('home'))->getContent(), '<footer id="publicFooter"');
        $this->assertStringContainsString(route('branding.file', 'ikon.png'), $footer);
        $this->assertStringNotContainsString(route('branding.file', 'logo-utama.png'), $footer);

        Setting::put('branding_display', 'logo_only');
        $footer = strstr($this->get(route('home'))->getContent(), '<footer id="publicFooter"');
        $this->assertStringContainsString(route('branding.file', 'logo-utama.png'), $footer);

        Setting::put('branding_display', 'text');
        $footer = strstr($this->get(route('home'))->getContent(), '<footer id="publicFooter"');
        $this->assertStringNotContainsString('branding/', $footer);
    }
}
