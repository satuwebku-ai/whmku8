<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\PromoBanner;
use App\Models\Setting;
use App\Support\ClientNotice;
use Database\Seeders\AnnouncementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientNoticeTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticker_is_hidden_without_announcements_and_shown_with_them(): void
    {
        $this->assertNull(ClientNotice::ticker());
        $this->assertStringNotContainsString('clientTicker', view('client.partials.notice-ticker')->render());

        $this->seed(AnnouncementSeeder::class);

        $html = view('client.partials.notice-ticker')->render();
        $this->assertStringContainsString('id="clientTicker"', $html);
        $this->assertStringContainsString('Selamat datang', $html);
    }

    public function test_ticker_can_be_switched_off_and_respects_the_age_limit(): void
    {
        Announcement::create(['title' => 'Lama', 'content' => '<p>x</p>', 'category' => 'info', 'is_published' => true, 'published_at' => now()->subDays(90)]);

        $this->assertNull(ClientNotice::ticker(), 'Pengumuman lebih dari 30 hari tidak tampil.');

        Setting::put('client_notice_days', '0');
        $this->assertNotNull(ClientNotice::ticker());

        Setting::put('client_ticker_enabled', '0');
        $this->assertNull(ClientNotice::ticker());
    }

    public function test_pinned_announcement_always_shows_and_incident_changes_the_tone(): void
    {
        Announcement::create(['title' => 'Sematan', 'content' => '<p>x</p>', 'category' => 'incident', 'is_published' => true, 'is_pinned' => true, 'published_at' => now()->subDays(200)]);

        $ticker = ClientNotice::ticker();
        $this->assertSame('#b91c1c', $ticker['bg']);
        $this->assertSame('Sematan', $ticker['items'][0]['title']);
    }

    public function test_client_banners_need_the_explicit_client_page(): void
    {
        $base = ['image' => 'a.png', 'title' => 'T', 'is_active' => true, 'sort_order' => 1];
        PromoBanner::create($base + ['display_page' => 'all']);
        $this->assertCount(0, ClientNotice::banners());

        PromoBanner::create($base + ['display_page' => 'client']);
        $this->assertCount(1, ClientNotice::banners());
    }

    public function test_admin_can_save_client_notice_settings(): void
    {
        $admin = Admin::create([
            'username' => 'cms', 'name' => 'CMS', 'email' => 'cms@contoh.test',
            'password' => bcrypt('Rahasia-123'), 'role' => 'superadmin', 'is_active' => true,
        ]);
        $this->actingAs($admin, 'admin');

        $this->get(route('admin.settings.client-notice'))->assertOk()->assertSee('Pengumuman di Area Klien');

        $this->post(route('admin.settings.client-notice.update'), [
            'client_ticker_speed' => 'fast', 'client_notice_days' => 7, 'client_ticker_enabled' => '1',
        ])->assertRedirect();

        $this->assertSame('fast', ClientNotice::speed());
        $this->assertSame(7, ClientNotice::windowDays());
        $this->assertTrue(ClientNotice::tickerEnabled());
        $this->assertFalse(ClientNotice::modalEnabled());
    }
}
