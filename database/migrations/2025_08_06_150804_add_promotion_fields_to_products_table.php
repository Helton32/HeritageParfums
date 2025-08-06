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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_on_promotion')->default(false)->after('price');
            $table->decimal('promotion_price', 8, 2)->nullable()->after('is_on_promotion');
            $table->date('promotion_start_date')->nullable()->after('promotion_price');
            $table->date('promotion_end_date')->nullable()->after('promotion_start_date');
            $table->text('promotion_description')->nullable()->after('promotion_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'is_on_promotion',
                'promotion_price',
                'promotion_start_date',
                'promotion_end_date',
                'promotion_description'
            ]);
        });
    }
};
