<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Setting;
use App\Support\ThemeRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreLayoutSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_always_get_the_public_layout(): void
    {
        $this->assertSame('public.layout', ThemeRegistry::storeLayout());
    }

    public function test_logged_in_client_gets_the_client_layout_when_public_and_client_themes_match(): void
    {
        Setting::put('public_template', 'default');
        Setting::put('client_template', 'default');
        $this->actingAs(Client::factory()->create(), 'client');

        $this->assertSame('client.layout', ThemeRegistry::storeLayout());
    }

    public function test_logged_in_client_keeps_the_public_layout_when_themes_differ(): void
    {
        Setting::put('public_template', 'namahost');
        Setting::put('client_template', 'default');
        $this->actingAs(Client::factory()->create(), 'client');

        $this->assertSame('public.layout', ThemeRegistry::storeLayout());
    }

    public function test_unknown_theme_keys_fall_back_to_default(): void
    {
        Setting::put('public_template', 'tema-yang-sudah-dihapus');

        $this->assertSame('default', ThemeRegistry::active('public'));
    }

    public function test_every_store_view_of_every_public_theme_uses_the_store_layout(): void
    {
        foreach (array_keys(ThemeRegistry::available('public')) as $theme) {
            foreach (['catalog', 'cart', 'licenses'] as $dir) {
                foreach (glob(resource_path("views/themes/public-themes/{$theme}/public/{$dir}/*.blade.php")) as $file) {
                    if (! str_contains((string) file_get_contents($file), '@extends')) {
                        continue; // partial (mis. _product-card)
                    }

                    $this->assertStringContainsString("@extends('public.store-layout')", file_get_contents($file), $file);
                    $this->assertStringNotContainsString("@extends('public.layout')", file_get_contents($file), $file);
                }
            }
        }
    }
}
