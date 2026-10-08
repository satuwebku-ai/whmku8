<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalSubdomainRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_old_admin_login_url_returns_to_the_public_homepage(): void
    {
        $this->get('http://localhost/admin/login')
            ->assertRedirect(route('home'));
    }

    public function test_old_admin_links_other_than_login_move_to_the_admin_subdomain(): void
    {
        $this->get('http://localhost/admin/dashboard?tab=activity')
            ->assertRedirect('https://admin.localhost/dashboard?tab=activity');
    }

    public function test_admin_login_is_available_on_the_admin_subdomain_root_path(): void
    {
        $this->get(route('admin.login'))
            ->assertOk();
    }

    public function test_client_login_is_available_on_the_member_subdomain(): void
    {
        $this->get(route('client.login'))
            ->assertOk();
    }

    public function test_old_client_login_url_moves_to_the_member_subdomain(): void
    {
        $this->get('http://localhost/client/login?from=header')
            ->assertRedirect('https://member.localhost/login?from=header');
    }

    public function test_old_portal_prefixes_on_each_subdomain_are_removed(): void
    {
        $this->get('https://admin.localhost/admin/login')
            ->assertRedirect('https://admin.localhost/login');

        $this->get('https://member.localhost/client/login')
            ->assertRedirect('https://member.localhost/login');
    }

    public function test_guests_on_each_portal_are_sent_to_the_matching_login(): void
    {
        $this->get('http://admin.localhost/')
            ->assertRedirect(route('admin.login'));

        $this->get('http://member.localhost/')
            ->assertRedirect(route('client.login'));
    }

    public function test_public_site_routes_are_not_exposed_on_the_portal_hosts(): void
    {
        $this->get('http://admin.localhost/hosting')->assertNotFound();
        $this->get('http://member.localhost/hosting')->assertNotFound();
    }

    public function test_store_routes_live_on_the_client_host_under_the_store_prefix(): void
    {
        foreach (['catalog.index' => '/store/hosting', 'catalog.vps' => '/store/vps', 'cart.index' => '/store/keranjang'] as $name => $path) {
            $url = parse_url(route($name));

            $this->assertSame(config('portal_domains.client'), $url['host'], $name);
            $this->assertSame($path, $url['path'], $name);
        }
    }

    public function test_store_is_browsable_by_guests_on_the_client_host(): void
    {
        $this->get(route('catalog.index'))->assertOk();
    }

    public function test_store_vps_catalog_does_not_collide_with_the_clients_own_vps_pages(): void
    {
        // /vps milik portal client (VPS Saya) tetap butuh login...
        $this->get('http://member.localhost/vps')->assertRedirect(route('client.login'));

        // ...sedangkan katalog VPS di /store/vps terbuka untuk tamu.
        $this->assertNotSame(route('client.login'), $this->get(route('catalog.vps'))->headers->get('Location'));
    }

    public function test_store_is_not_served_on_the_public_or_admin_hosts(): void
    {
        $this->get('http://localhost/store/hosting')->assertNotFound();
        $this->get('http://admin.localhost/store/hosting')->assertNotFound();
    }

    public function test_old_public_store_addresses_redirect_to_the_client_store(): void
    {
        $this->get('http://localhost/hosting')->assertStatus(301)->assertRedirect(route('catalog.index'));
        $this->get('http://localhost/vps')->assertStatus(301)->assertRedirect(route('catalog.vps'));
        $this->get('http://localhost/keranjang')->assertStatus(301)->assertRedirect(route('cart.index'));
        $this->get('http://localhost/lisensi')->assertStatus(301)->assertRedirect(route('license.index'));
    }

    public function test_old_public_store_redirects_keep_the_query_string(): void
    {
        $this->get('http://localhost/cek-domain?domain=contoh.com')
            ->assertStatus(301)
            ->assertRedirect(route('domain.search').'?domain=contoh.com');
    }
}
