<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending')->after('amount');
            $table->string('payment_note')->nullable()->after('payment_status');
            $table->index(['paid_month', 'paid_year']);
        });
    }
    
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->dropColumn('payment_note');
            $table->dropIndex(['paid_month', 'paid_year']);
        });
    }
    
};
