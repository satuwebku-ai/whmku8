<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('server_groups', function (Blueprint $table) {
            $table->string('location')->nullable();
            $table->unsignedInteger('priority')->default(0);
            $table->string('status', 20)->default('active')->index();
        });

        Schema::create('server_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('disk_limit')->nullable();
            $table->unsignedInteger('bandwidth_limit')->nullable();
            $table->unsignedSmallInteger('cpu_limit')->nullable();
            $table->unsignedInteger('ram_limit')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->unique(['server_id', 'name']);
            $table->index(['server_id', 'status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('server_package_id')
                ->nullable()
                ->constrained('server_packages')
                ->nullOnDelete();
        });

        Schema::table('hosting_accounts', function (Blueprint $table) {
            $table->foreignId('server_package_id')
                ->nullable()
                ->constrained('server_packages')
                ->nullOnDelete();
        });

        Schema::create('provisionings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('hosting_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('server_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('server_package_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('attempt_number')->default(1);
            $table->string('status', 20)->index();
            $table->text('message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            $table->index(['hosting_account_id', 'created_at'], 'provisionings_account_created_idx');
            $table->index(['order_id', 'created_at'], 'provisionings_order_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provisionings');

        Schema::table('hosting_accounts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('server_package_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('server_package_id');
        });

        Schema::dropIfExists('server_packages');

        Schema::table('server_groups', function (Blueprint $table) {
            $table->dropIndex('server_groups_status_index');
            $table->dropColumn(['location', 'priority', 'status']);
        });
    }
};
