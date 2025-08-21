<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            // Drop old column if exists
            if (Schema::hasColumn('sale_items', 'subtotal')) {
                $table->dropColumn('subtotal');
            }

            // Add new columns
            $table->decimal('total', 12, 2)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            // Reverse changes
            $table->decimal('subtotal', 12, 2)->after('price');
            $table->dropColumn('total');
        });
    }
};
