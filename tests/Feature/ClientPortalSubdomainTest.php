<?php

namespace Tests\Feature;

use Tests\TestCase;

class ClientPortalSubdomainTest extends TestCase
{
    private array $savedEnvironment = [];

    protected function setUp(): void
    {
        $this->setProcessEnvironment('APP_URL', 'http://public.example.test');
        $this->setProcessEnvironment('PUBLIC_SITE_HOST', 'public.example.test');
        $this->setProcessEnvironment('CLIENT_PORTAL_HOST', 'client.example.test');

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        foreach ($this->savedEnvironment as $key => $state) {
            if ($state['process'] === false) {
                putenv($key);
            } else {
                putenv($key . '=' . $state['process']);
            }

            if ($state['server_exists']) {
                $_SERVER[$key] = $state['server'];
            } else {
                unset($_SERVER[$key]);
            }

            if ($state['env_exists']) {
                $_ENV[$key] = $state['env'];
            } else {
                unset($_ENV[$key]);
            }
        }
    }

    public function test_client_routes_use_portal_host_and_keep_their_existing_route_names(): void
    {
        $this->assertStringStartsWith('http://client.example.test/client/login', route('client.login'));
        $this->assertStringStartsWith('http://public.example.test', route('home'));
    }

    public function test_client_root_is_the_storefront_and_portal_is_available_under_client_path(): void
    {
        $this->get('http://client.example.test/')
            ->assertOk()
            ->assertSee('Secure your domain name');

        $this->get('http://client.example.test/client')
            ->assertRedirect('http://client.example.test/client/login');

        $this->get('http://client.example.test/hosting')->assertOk();
        $this->get('http://outside.example.test/hosting')->assertNotFound();
    }

    public function test_old_client_get_urls_redirect_to_the_portal_and_keep_the_query_string(): void
    {
        $this->get('http://public.example.test/client/login?next=billing')
            ->assertRedirect('http://client.example.test/client/login?next=billing');
    }

    private function setProcessEnvironment(string $key, string $value): void
    {
        if (! array_key_exists($key, $this->savedEnvironment)) {
            $this->savedEnvironment[$key] = [
                'process' => getenv($key),
                'server_exists' => array_key_exists($key, $_SERVER),
                'server' => $_SERVER[$key] ?? null,
                'env_exists' => array_key_exists($key, $_ENV),
                'env' => $_ENV[$key] ?? null,
            ];
        }

        putenv($key . '=' . $value);
        $_SERVER[$key] = $value;
        $_ENV[$key] = $value;
    }
}
