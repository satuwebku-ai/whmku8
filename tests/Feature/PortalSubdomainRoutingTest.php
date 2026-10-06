<?php

namespace Tests\Feature;

use Tests\TestCase;

class PortalSubdomainRoutingTest extends TestCase
{
    public function test_client_and_admin_login_routes_use_their_configured_hosts(): void
    {
        $clientUrl = route('client.login');
        $adminUrl = route('admin.login');
        $serverEditUrl = route('admin.servers.edit', ['server' => '__ID__']);

        $this->assertSame(config('portals.client_host'), parse_url($clientUrl, PHP_URL_HOST));
        $this->assertSame(config('portals.admin_host'), parse_url($adminUrl, PHP_URL_HOST));
        $this->assertSame('/login', parse_url($clientUrl, PHP_URL_PATH));
        $this->assertSame('/login', parse_url($adminUrl, PHP_URL_PATH));
        $this->assertSame(config('portals.admin_host'), parse_url($serverEditUrl, PHP_URL_HOST));
        $this->assertSame('/servers/__ID__/edit', parse_url($serverEditUrl, PHP_URL_PATH));
    }

    public function test_portal_roots_redirect_guests_to_the_matching_login_host(): void
    {
        $this->get('http://'.config('portals.client_host').'/')
            ->assertRedirect(route('client.login'));

        $this->get('http://'.config('portals.admin_host').'/')
            ->assertRedirect(route('admin.login'));
    }

    public function test_legacy_portal_urls_redirect_to_the_new_host_and_path(): void
    {
        $this->get('/admin/tickets?from=bookmark')
            ->assertRedirect(route('admin.tickets').'?from=bookmark');

        $this->get('/client/services')
            ->assertRedirect(route('client.services'));
    }
}
