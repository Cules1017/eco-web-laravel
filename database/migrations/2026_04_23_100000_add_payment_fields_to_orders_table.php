<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_transaction_id')) {
                $table->string('payment_transaction_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'payment_request_id')) {
                $table->string('payment_request_id')->nullable()->after('payment_transaction_id');
            }
            if (!Schema::hasColumn('orders', 'payment_payload')) {
                $table->json('payment_payload')->nullable()->after('payment_request_id');
            }
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_payload');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_transaction_id', 'payment_request_id', 'payment_payload', 'paid_at']);
        });
    }
};
