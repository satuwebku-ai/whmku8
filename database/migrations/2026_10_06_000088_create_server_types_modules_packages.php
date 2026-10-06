<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_types', function (Blueprint $table) {
            $table->id();
            $table->string('key', 30)->unique(); // shared | vps | dedicated
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique(); // cpanel | directadmin | plesk | vps:<provider>
            $table->string('name', 100);
            $table->string('type', 50)->default('panel'); // panel | vps
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('server_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100); // nama plan di panel, harus sama persis
            $table->unsignedInteger('disk_limit_mb')->nullable();
            $table->unsignedInteger('bandwidth_limit_mb')->nullable();
            $table->unsignedSmallInteger('cpu_limit')->nullable();
            $table->unsignedInteger('ram_limit_mb')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['server_id', 'name']);
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->foreignId('server_type_id')->nullable()->after('server_group_id')->constrained('server_types')->nullOnDelete();
            $table->foreignId('module_id')->nullable()->after('server_type_id')->constrained('modules')->nullOnDelete();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('server_package_id')->nullable()->after('server_id')->constrained('server_packages')->nullOnDelete();
        });

        // Data master + isi awal dari server yang sudah ada.
        $now = now();
        foreach ([['shared', 'Shared Hosting'], ['vps', 'VPS'], ['dedicated', 'Dedicated']] as [$key, $name]) {
            DB::table('server_types')->insert(['key' => $key, 'name' => $name, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }
        foreach ([['cpanel', 'cPanel/WHM', 'panel'], ['directadmin', 'DirectAdmin', 'panel'], ['plesk', 'Plesk', 'panel']] as [$key, $name, $type]) {
            DB::table('modules')->insert(['key' => $key, 'name' => $name, 'type' => $type, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }

        $typeIds = DB::table('server_types')->pluck('id', 'key');
        foreach (DB::table('servers')->get() as $s) {
            $isCloud = $s->panel === 'vps' || ! empty($s->vps_provider);
            $moduleKey = $isCloud ? 'vps:' . ($s->vps_provider ?: 'generic') : $s->panel;
            $module = DB::table('modules')->where('key', $moduleKey)->first();
            if (! $module) {
                $id = DB::table('modules')->insertGetId([
                    'key' => $moduleKey, 'name' => $isCloud ? 'VPS ' . ($s->vps_provider ?: '') : $moduleKey,
                    'type' => $isCloud ? 'vps' : 'panel', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
                ]);
            } else {
                $id = $module->id;
            }
            DB::table('servers')->where('id', $s->id)->update([
                'server_type_id' => $typeIds[$isCloud ? 'vps' : 'shared'] ?? null,
                'module_id' => $id,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('server_package_id');
        });
        Schema::table('servers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('module_id');
            $table->dropConstrainedForeignId('server_type_id');
        });
        Schema::dropIfExists('server_packages');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('server_types');
    }
};
