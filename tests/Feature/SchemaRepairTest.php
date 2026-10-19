<?php

namespace Tests\Feature;

use App\Support\SchemaRepair;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * SchemaRepair melengkapi database lama (hasil restore backup) dengan kolom/tabel
 * yang dulu ditambahkan migrasi terpisah. Skenario backup sungguhan -- 16 titik
 * waktu, dari migrasi lama ke skema baru -- sudah diuji manual di SQLite; tes di
 * sini menjaga agar definisinya tidak menyimpang dari migrasi induk.
 *
 * Catatan: pelebaran enum -> string (chat_conversations.channel, credits.type)
 * hanya terdeteksi di MySQL, jadi tidak tercakup oleh tes yang berjalan di SQLite.
 */
class SchemaRepairTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_freshly_migrated_database_needs_no_repair(): void
    {
        // Penjaga penyimpangan: semua yang diketahui SchemaRepair harus sudah ada
        // di skema hasil migrate. Kalau gagal, definisi di SchemaRepair dan di
        // migrasi induk tidak lagi sama.
        $this->assertSame([], SchemaRepair::run(true));
    }

    public function test_it_restores_columns_and_tables_missing_from_an_old_database(): void
    {
        Schema::table('clients', fn ($t) => $t->dropColumn([
            'pending_email', 'pending_email_code_hash', 'pending_email_expires_at',
            'pending_email_attempts', 'password_otp_enabled', 'password_set_by_user',
        ]));
        Schema::table('servers', fn ($t) => $t->dropColumn(['priority', 'is_maintenance']));
        Schema::table('hosting_accounts', fn ($t) => $t->dropColumn([
            'pending_upgrade_price', 'panel_suspend_error', 'credentials_sent_at', 'credentials_email_failed_at',
        ]));
        Schema::table('tickets', fn ($t) => $t->dropColumn('idle_prompted_at'));
        Schema::dropIfExists('server_group_server');
        Schema::dropIfExists('mail_templates');

        $planned = SchemaRepair::run(true);
        $this->assertNotEmpty($planned);
        $this->assertFalse(Schema::hasColumn('servers', 'priority'), 'dry-run tidak boleh mengubah skema');

        $done = SchemaRepair::run(false);

        $this->assertSame($planned, $done);
        $this->assertTrue(Schema::hasColumn('clients', 'pending_email'));
        $this->assertTrue(Schema::hasColumn('clients', 'password_set_by_user'));
        $this->assertTrue(Schema::hasColumn('servers', 'priority'));
        $this->assertTrue(Schema::hasColumn('servers', 'is_maintenance'));
        $this->assertTrue(Schema::hasColumn('hosting_accounts', 'pending_upgrade_price'));
        $this->assertTrue(Schema::hasColumn('tickets', 'idle_prompted_at'));
        $this->assertTrue(Schema::hasTable('server_group_server'));
        $this->assertTrue(Schema::hasTable('mail_templates'));
    }

    public function test_it_is_idempotent(): void
    {
        Schema::table('servers', fn ($t) => $t->dropColumn('is_maintenance'));

        $this->assertNotEmpty(SchemaRepair::run(false));
        $this->assertSame([], SchemaRepair::run(false));
        $this->assertSame([], SchemaRepair::run(true));
    }

    public function test_sync_migrations_reports_a_complete_schema_on_a_current_database(): void
    {
        $this->artisan('lumora:sync-migrations', ['--dry-run' => true])
            ->expectsOutputToContain('skema sudah lengkap')
            ->assertSuccessful();
    }

    public function test_sync_migrations_repairs_the_schema_unless_told_not_to(): void
    {
        Schema::table('servers', fn ($t) => $t->dropColumn('is_maintenance'));

        $this->artisan('lumora:sync-migrations', ['--no-repair' => true])->assertSuccessful();
        $this->assertFalse(Schema::hasColumn('servers', 'is_maintenance'));

        $this->artisan('lumora:sync-migrations')
            ->expectsOutputToContain('servers.is_maintenance')
            ->assertSuccessful();
        $this->assertTrue(Schema::hasColumn('servers', 'is_maintenance'));
    }

    public function test_it_restores_the_columns_merged_from_the_invoice_items_and_nav_menus_migrations(): void
    {
        // Backup dari sebelum migrasi add_service_reference_to_invoice_items dan add_target_to_nav_menus.
        Schema::table('invoice_items', fn ($t) => $t->dropIndex('invoice_items_service_index'));
        Schema::table('invoice_items', fn ($t) => $t->dropColumn(['service_type', 'service_id', 'item_type']));
        Schema::table('nav_menus', fn ($t) => $t->dropColumn('target'));

        $planned = SchemaRepair::run(true);
        $this->assertContains('tambah kolom invoice_items.service_type', $planned);
        $this->assertContains('tambah kolom nav_menus.target', $planned);
        $this->assertContains('tambah index invoice_items_service_index', $planned);

        $this->assertSame($planned, SchemaRepair::run(false));

        $this->assertTrue(Schema::hasColumns('invoice_items', ['service_type', 'service_id', 'item_type']));
        $this->assertTrue(Schema::hasIndex('invoice_items', 'invoice_items_service_index'));
        $this->assertTrue(Schema::hasColumn('nav_menus', 'target'));
        $this->assertSame([], SchemaRepair::run(true));
    }

    public function test_sync_migrations_cleans_stale_history_rows_left_by_older_backups(): void
    {
        // Migrasi add_* yang sudah dilebur ke induknya: barisnya dibuang.
        DB::table('migrations')->insert([
            ['migration' => '2026_01_01_000075_add_service_reference_to_invoice_items_table', 'batch' => 1],
            ['migration' => '2026_10_16_000001_add_target_to_nav_menus_table', 'batch' => 1],
        ]);

        // Migrasi induk yang pernah bernomor lain: barisnya diganti nama ke file sekarang.
        DB::table('migrations')->where('migration', '2026_01_01_000026_create_invoice_items_table')->delete();
        DB::table('migrations')->insert(['migration' => '2026_09_09_000001_create_invoice_items_table', 'batch' => 1]);

        $this->artisan('lumora:sync-migrations')->assertSuccessful();

        $names = DB::table('migrations')->pluck('migration')->all();
        $this->assertNotContains('2026_01_01_000075_add_service_reference_to_invoice_items_table', $names);
        $this->assertNotContains('2026_10_16_000001_add_target_to_nav_menus_table', $names);
        $this->assertNotContains('2026_09_09_000001_create_invoice_items_table', $names);
        $this->assertContains('2026_01_01_000026_create_invoice_items_table', $names);
    }
}
