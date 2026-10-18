<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_saved_section_order_is_what_the_public_home_uses(): void
    {
        $admin = Admin::create([
            'username' => 'cms', 'name' => 'CMS', 'email' => 'cms@contoh.test',
            'password' => bcrypt('Rahasia-123'), 'role' => 'superadmin', 'is_active' => true,
        ]);
        $this->actingAs($admin, 'admin');

        $this->post(route('admin.settings.homepage.update'), [
            'home_categories_limit' => 6, 'home_featured_limit' => 3,
            'home_vps_limit' => 3, 'home_announcements_limit' => 3,
            'section_order' => 'cta,benefits,domain',
            'home_show_cta' => '1', 'home_show_benefits' => '1', 'home_show_domain' => '1',
        ])->assertRedirect();

        $saved = json_decode((string) Setting::get('home_section_order'), true);
        $this->assertSame(['cta', 'benefits', 'domain'], array_slice($saved, 0, 3));

        $this->get(route('home'))
            ->assertOk()
            ->assertViewHas('homeOrder', fn (array $order) => array_slice($order, 0, 3) === ['cta', 'benefits', 'domain']);
    }
}
