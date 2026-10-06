<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Riwayat provisioning per percobaan (diagram: entitas "Provisioning").
        Schema::create('provisioning_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hosting_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('server_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 20)->default('running'); // running | success | failed
            $table->text('message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
            $table->index(['hosting_account_id', 'status']);
        });

        // Gabungan suspend_log / unsuspend_log / terminate_log dari diagram,
        // dibedakan oleh kolom "event".
        Schema::create('service_lifecycle_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hosting_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event', 20); // suspend | unsuspend | terminate
            $table->string('reason', 30)->nullable(); // overdue | request | expired | payment | other
            $table->text('note')->nullable();
            $table->timestamp('event_date')->useCurrent();
            $table->string('status', 20)->default('success'); // pending | success | failed
            $table->timestamps();
            $table->index(['hosting_account_id', 'event']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_lifecycle_logs');
        Schema::dropIfExists('provisioning_jobs');
    }
};
