<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 26)->unique();
            $table->char('client_request_id', 36)->nullable()->unique();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('branch_unit_id');
            $table->unsignedBigInteger('register_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->integer('order_number');
            $table->date('order_date');
            $table->enum('type', ['dine_in','takeaway','delivery'])->default('takeaway');
            $table->enum('status', ['open','awaiting_payment','paid','voided','refunded'])->default('open');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->integer('subtotal_cents')->default(0);
            $table->integer('discount_cents')->default(0);
            $table->integer('tax_cents')->default(0);
            $table->integer('total_cents')->default(0);
            $table->integer('loyalty_earned')->default(0);
            $table->integer('loyalty_redeemed')->default(0);
            $table->string('notes', 500)->nullable();
            $table->timestamps();
            $table->unique(['branch_id', 'order_date', 'order_number']);
            $table->index(['branch_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->integer('line_number');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('sku', 64);
            $table->string('name', 200);
            $table->decimal('qty', 12, 3)->default(1);
            $table->integer('unit_price_cents');
            $table->integer('discount_cents')->default(0);
            $table->integer('tax_rate_bp');
            $table->integer('tax_cents')->default(0);
            $table->integer('total_cents')->default(0);
            $table->string('route_station', 64)->nullable();
            $table->enum('status', ['new','prepping','ready','served','voided'])->default('new');
            $table->string('notes', 300)->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'line_number']);
            $table->index(['route_station', 'status']);
        });

        Schema::create('order_item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->string('name', 120);
            $table->integer('price_delta_cents')->default(0);
            $table->timestamps();
            $table->index('order_item_id');
        });

        // Payments & Receipts
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 26)->unique();
            $table->char('client_request_id', 36)->nullable()->unique();
            $table->unsignedBigInteger('order_id');
            $table->enum('method', ['cash','card','qr','wallet','other']);
            $table->integer('amount_cents');
            $table->integer('change_cents')->default(0);
            $table->string('provider', 64)->nullable();
            $table->string('provider_txn_id', 100)->nullable();
            $table->enum('status', ['authorized','captured','failed','voided','refunded'])->default('captured');
            $table->timestamp('captured_at')->nullable();
            $table->timestamps();
            $table->index('order_id');
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('register_id')->nullable();
            $table->integer('receipt_number');
            $table->date('receipt_date');
            $table->boolean('is_reprint')->default(false);
            $table->unsignedBigInteger('reprint_of')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();
            $table->unique(['register_id', 'receipt_date', 'receipt_number']);
            $table->index('order_id');
        });

        // Kitchen
        Schema::create('kitchen_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->integer('ticket_number');
            $table->string('station', 64);
            $table->enum('status', ['queued','prepping','ready','served','voided'])->default('queued');
            $table->timestamp('routed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamps();
            $table->unique(['station', 'ticket_number', 'routed_at']);
            $table->index(['station', 'status', 'routed_at']);
        });

        Schema::create('kitchen_ticket_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kitchen_ticket_id');
            $table->unsignedBigInteger('order_item_id');
            $table->decimal('qty', 12, 3)->default(1);
            $table->string('notes', 300)->nullable();
            $table->enum('status', ['queued','prepping','ready','served','voided'])->default('queued');
            $table->timestamps();
            $table->index('kitchen_ticket_id');
            $table->index('order_item_id');
        });

        // Reliability
        Schema::create('outbox_events', function (Blueprint $table) {
            $table->id();
            $table->string('aggregate_type', 64);
            $table->unsignedBigInteger('aggregate_id');
            $table->string('event_type', 64);
            $table->json('payload');
            $table->json('headers')->nullable();
            $table->timestamp('queued_at');
            $table->timestamp('published_at')->nullable();
            $table->integer('attempts')->default(0);
            $table->string('last_error', 500)->nullable();
            $table->timestamps();
            $table->index(['published_at', 'queued_at']);
            $table->index(['aggregate_type', 'aggregate_id', 'id']);
        });

        Schema::create('processed_events', function (Blueprint $table) {
            $table->id();
            $table->string('source', 64);
            $table->string('event_id', 100);
            $table->timestamp('processed_at');
            $table->unique(['source', 'event_id']);
        });

        Schema::create('idempotency_keys', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint', 120);
            $table->string('idempotency_key', 100);
            $table->integer('status_code');
            $table->mediumText('response_body')->nullable();
            $table->timestamp('created_at');
            $table->unique(['endpoint', 'idempotency_key']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('actor_type', 32);
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('action', 64);
            $table->string('subject_type', 64)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('occurred_at');
            $table->index('occurred_at');
            $table->index(['actor_type', 'actor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('idempotency_keys');
        Schema::dropIfExists('processed_events');
        Schema::dropIfExists('outbox_events');
        Schema::dropIfExists('kitchen_ticket_items');
        Schema::dropIfExists('kitchen_tickets');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_item_modifiers');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
