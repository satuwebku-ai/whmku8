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
}
