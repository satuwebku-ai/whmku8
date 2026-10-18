<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAdminGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_paths_on_public_host_return_home(): void
    {
        foreach (['/admin', '/admin/login', '/admin/dashboard?x=1'] as $path) {
            $this->get('http://localhost'.$path)->assertStatus(302)->assertRedirect(route('home'));
        }
    }

    public function test_public_menu_pages_still_open(): void
    {
        $this->get(route('catalog.index'))->assertOk();
    }

    public function test_www_host_redirects_to_apex_keeping_path(): void
    {
        $this->get('http://www.localhost/admin/login?a=1')
            ->assertStatus(308)
            ->assertRedirect('https://localhost/admin/login?a=1');
    }

    public function test_unknown_host_admin_path_goes_to_public_home(): void
    {
        $this->get('http://203.0.113.10/admin/login')
            ->assertStatus(302)
            ->assertRedirect('https://localhost/');
    }

    public function test_admin_login_still_works_on_admin_host(): void
    {
        $this->get(route('admin.login'))->assertOk();
    }
}
