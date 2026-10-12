<?php

namespace Tests\Feature\Domain;

use App\Models\CustomPremiumDomain;
use App\Models\Registrar;
use App\Models\Tld;
use App\Models\TldPremium;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainSearchPremiumClassificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_custom_premium_search_result_uses_its_own_price_and_cart_route(): void
    {
        $this->createTld('.com', 120000);
        CustomPremiumDomain::create([
            'domain_name' => 'brand.com',
            'label' => 'brand',
            'extension' => '.com',
            'characters' => 5,
            'years' => 1,
            'cost_price' => 250000,
            'sell_price' => 375000,
            'is_active' => true,
        ]);

        Http::fake([
            'https://rdap.org/domain/*' => Http::response([], 404),
        ]);

        $this->get(route('domain.search', ['domain' => 'brand']))
            ->assertOk()
            ->assertSee('PREMIUM KHUSUS')
            ->assertSee('Rp 375.000')
            ->assertSee(route('cart.add-custom-premium'), false)
            ->assertSee('name="custom_premium_id"', false)
            ->assertDontSee('name="domain_name" value="brand.com"', false);
    }

    public function test_regular_domain_search_result_keeps_regular_price_and_cart_route(): void
    {
        $this->createTld('.com', 120000);

        Http::fake([
            'https://rdap.org/domain/*' => Http::response([], 404),
        ]);

        $this->get(route('domain.search', ['domain' => 'example']))
            ->assertOk()
            ->assertSee('Rp 120.000')
            ->assertSee(route('domain.add-to-cart'), false)
            ->assertDontSee('PREMIUM KHUSUS')
            ->assertDontSee('Tambah Premium');
    }

    public function test_id_family_premium_search_result_uses_the_premium_tier(): void
    {
        $registrar = Registrar::create([
            'name' => 'DNAMA Test',
            'provider' => 'dnama',
            'api_url' => 'https://api.dnama.test',
            'api_username' => 'test-user',
            'api_key' => 'test-key',
            'is_active' => true,
        ]);

        $this->createTld('.id', 250000, $registrar->id);
        TldPremium::create([
            'registrar_id' => $registrar->id,
            'extension' => '.id',
            'is_premium' => true,
            'max_premium_character' => 2,
            'label' => '* .id (2 karakter) Premium',
            'is_generic' => false,
            'cost_register' => 3000000,
            'sell_register_price' => 8500000,
            'is_active' => true,
        ]);

        Http::fake([
            'https://rdap.org/domain/*' => Http::response([], 404),
            'https://api.dnama.test/domain-availability*' => Http::response([
                'data' => ['available' => true, 'is_premium' => true],
            ]),
        ]);

        $this->get(route('domain.search', ['domain' => 'ab.id']))
            ->assertOk()
            ->assertSee('PREMIUM')
            ->assertSee('Rp 8.500.000')
            ->assertDontSee('Rp 3.000.000')
            ->assertSee(route('cart.add-premium-domain'), false)
            ->assertSee('name="tld_premium_id"', false)
            ->assertDontSee('name="domain_name" value="ab.id"', false);
    }

    private function createTld(string $extension, float $price, ?int $registrarId = null): Tld
    {
        return Tld::create([
            'extension' => $extension,
            'registrar_id' => $registrarId,
            'register_price' => $price,
            'renew_price' => $price,
            'transfer_price' => $price,
            'is_active' => true,
            'show_in_search' => true,
            'search_group' => 'Populer',
        ]);
    }
}
